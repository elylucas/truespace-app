# PRD: Per-User Randomized Question Ordering

**Status:** Draft
**Date:** 2026-03-04
**Branch:** `feature/randomized-question-ordering`

---

## Overview

Today every user sees assessment questions in the same fixed `sort_order` sequence. This feature assigns each user a unique, randomly shuffled question order for each assessment. The order is generated on first visit and persists permanently — the user always sees the same sequence on return visits.

---

## Requirements

| # | Requirement |
|---|---|
| 1 | Each user receives a unique random ordering of questions for each assessment |
| 2 | The ordering is generated once (on first visit to `/take`) and stored persistently |
| 3 | Returning to the assessment always resumes the same order |
| 4 | Existing users with prior responses receive a new random order on their next visit |
| 5 | The order never resets (not when assessment status changes, not on re-visit) |
| 6 | Response storage is unaffected — responses remain keyed by `question_id` |
| 7 | Admin `sort_order` column is preserved for admin/display use |

---

## Data Model

### New Table: `user_assessment_orders`

| Column | Type | Notes |
|---|---|---|
| `id` | bigIncrements | PK |
| `user_id` | foreignId | FK → users, cascade delete |
| `assessment_id` | foreignId | FK → assessments, cascade delete |
| `question_order` | json | Ordered array of question IDs, e.g. `[4, 1, 6, 2, 5, 3]` |
| `created_at` | timestamp | When the order was first assigned |
| `updated_at` | timestamp | |
| UNIQUE | `(user_id, assessment_id)` | One row per user per assessment |

---

## Files Changed

### 1. `database/migrations/..._create_user_assessment_orders_table.php` *(new)*

Creates the `user_assessment_orders` table with the schema above.

**Why:** Persistent per-user order requires a dedicated storage row. Using a JSON column for the ordered ID array is a single-row lookup — no joins needed to reconstruct the sequence.

---

### 2. `app/Models/UserAssessmentOrder.php` *(new)*

```php
class UserAssessmentOrder extends Model
{
    protected $fillable = ['user_id', 'assessment_id', 'question_order'];
    protected $casts = ['question_order' => 'array'];

    public function user(): BelongsTo { ... }
    public function assessment(): BelongsTo { ... }
}
```

**Why:** Encapsulates the order record as a proper Eloquent model with casting, so `question_order` is always a PHP array when accessed.

---

### 3. `app/Livewire/TakeAssessment.php` *(modified)*

Update `mount()` to look up or create an order record before loading questions:

```php
public function mount(Assessment $assessment): void
{
    $this->assessment = $assessment;

    // Get or create a persistent random order for this user + assessment
    $order = UserAssessmentOrder::firstOrCreate(
        [
            'user_id'       => auth()->id(),
            'assessment_id' => $assessment->id,
        ],
        [
            'question_order' => $assessment->questions()
                                    ->pluck('id')
                                    ->shuffle()
                                    ->values()
                                    ->toArray(),
        ]
    );

    // Load questions sorted by the stored order
    $orderedIds    = $order->question_order;
    $questionsById = $assessment->questions()->get()->keyBy('id');

    $this->questions = collect($orderedIds)
        ->map(fn($id) => $questionsById[$id] ?? null)
        ->filter()
        ->values()
        ->toArray();

    // Load existing responses (unchanged)
    $existing = Response::where('assessment_id', $assessment->id)
        ->where('user_id', auth()->id())
        ->pluck('value', 'question_id');

    foreach ($this->questions as $q) {
        $this->answers[$q['id']] = $existing[$q['id']] ?? '';
    }
}
```

**Why `firstOrCreate`:** Atomically handles both the "first visit" (creates + shuffles) and "return visit" (fetches existing) cases in one call, with no race conditions.

**Why map by ID:** Avoids a second DB query — questions are fetched once, keyed by ID, then reordered in PHP using the stored array.

---

### 4. `app/Models/User.php` *(modified)*

Add relationship:

```php
public function assessmentOrders(): HasMany
{
    return $this->hasMany(UserAssessmentOrder::class);
}
```

**Why:** Keeps the model graph complete for any future admin tooling (e.g. viewing a user's assigned orders).

---

## What Is NOT Changed

| Area | Reason |
|---|---|
| `questions.sort_order` column | Preserved for admin display and future use |
| Response table and storage logic | Responses are keyed by `question_id` — order is irrelevant |
| `Assessment::questions()` relationship | Still orders by `sort_order` for admin views |
| All views | No template changes needed |
| `AssessmentController` | No changes needed |

---

## Edge Cases

| Scenario | Behavior |
|---|---|
| User visits `/take` for the first time | Random order generated and saved; user proceeds |
| User returns mid-completion | Same order loaded; existing answers pre-filled (unchanged) |
| User with seeded responses (no order record yet) | New random order generated on next visit; prior answers still load correctly via `question_id` lookup |
| Question added to assessment after user's order was set | New question is appended to end of their stored order on next `mount()` call |
| Question deleted from assessment | `filter()` in mount() silently drops the missing ID |

---

## Permissions Changes (bundled in this branch)

While implementing question ordering, role-based access controls were also added to restrict assessment editing and user management to authorized roles.

### Gates defined in `app/Providers/AppServiceProvider.php`

| Gate | Allowed roles |
|---|---|
| `admin` | `admin` |
| `manage-assessments` | `admin`, `manager` |

### Route protection in `routes/web.php`

| Route | Middleware |
|---|---|
| `GET /assessments/{assessment}/edit` | `can:manage-assessments` |
| `PUT /assessments/{assessment}` | `can:manage-assessments` |
| `GET /users` | `can:admin` |

### UI changes

- Edit link hidden in `assessments/index.blade.php` for non-admin/manager users (`@can('manage-assessments')`)
- Edit button hidden in `assessments/show.blade.php` for non-admin/manager users (`@can('manage-assessments')`)
- Users nav link hidden in `layouts/navigation.blade.php` for non-admin users (`@can('admin')`)

---

## Out of Scope

- Admin UI to view or override a user's question order
- Re-randomization controls
- Analytics on question position vs. response patterns

---

## Deliverable

A GitHub PR against `main` (branched from `feature/truespace-styling`) containing the migration, model, and Livewire changes, with this PRD linked in the PR description.
