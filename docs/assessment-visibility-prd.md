# PRD: Assessment Visibility & Member Progress

**Status:** Complete
**Date:** 2026-03-04
**Branch:** `feature/ask-trudy`

---

## Overview

Two related improvements to the assessments list for member-role users:

1. **Draft visibility** — Members should not see draft assessments. Drafts are works in progress and have no "Take Assessment" button anyway, making them confusing noise for respondents. Admins and managers retain full visibility of all statuses.

2. **Member progress column** — Replace the aggregate "Responses" column (total responses across all users, not meaningful to a member) with a personal "Your Progress" column showing how many questions the member has answered in each assessment.

---

## Requirements

| # | Requirement |
|---|---|
| 1 | Members only see assessments with `status = 'active'` in the list |
| 2 | Members who attempt to directly navigate to a draft assessment URL receive a 404 |
| 3 | The Status column and status filter dropdown are hidden for members |
| 4 | The Responses column is replaced with a "Your Progress" column for members |
| 5 | Progress shows "X / Y answered" in yellow when partially completed, green when fully answered |
| 6 | Progress shows "Not started" in muted gray when the member has no responses |
| 7 | Admins and managers see the original Responses column and Status column unchanged |

---

## Files Changed

### `app/Http/Controllers/AssessmentController.php`

**`index()` method:**
- Conditionally filters the query to `where('status', 'active')` for members
- Fetches a per-assessment answer count for the authenticated member via a single aggregated query:
  ```php
  Response::where('user_id', auth()->id())
      ->whereIn('assessment_id', $assessments->pluck('id'))
      ->selectRaw('assessment_id, count(*) as count')
      ->groupBy('assessment_id')
      ->pluck('count', 'assessment_id')
  ```
- Passes `$userAnswerCounts` (empty array for non-members) to the view

**`show()` method:**
- Added an early `abort(404)` guard for members attempting to access a non-active assessment directly via URL

### `resources/views/assessments/index.blade.php`

| Element | Admin/Manager | Member |
|---|---|---|
| Status filter dropdown | Shown | Hidden (`@can('manage-assessments')`) |
| Status column header | Shown | Hidden |
| Status badge cell | Shown | Hidden |
| Responses column header | Shown | Replaced with "Your Progress" |
| Responses cell | Shows total response count | Shows "X / Y answered" or "Not started" |

---

## Design Decisions

**Why 404 instead of redirect for direct URL access?**
A 404 makes draft assessments completely invisible to members — there's no indication the URL exists. A redirect would expose that the assessment exists but is inaccessible, which could be confusing.

**Why a single aggregated query for answer counts?**
Rather than N+1 queries (one per assessment), a single `whereIn` + `groupBy` query fetches all counts at once and maps them by assessment ID. The view then does an O(1) array lookup per row.

**Why `@can('manage-assessments')` for the UI gates?**
Reuses the existing gate that already controls edit access, keeping the permission model consistent — admins and managers can see and manage assessments fully, members are respondents only.

---

## Out of Scope

- Showing member progress on the assessment show page
- Percentage-based progress bars
- "Completed" vs "in progress" distinction (the app has no explicit submission record per user)
