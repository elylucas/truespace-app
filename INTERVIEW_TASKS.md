# Interview Tasks

Welcome! This exercise is designed to evaluate how you approach real-world feature development in a Laravel application. There are two parts — the first is more concrete, and the second is intentionally open-ended.

Take your time, write code you're proud of, and explain your thinking in the PR description.

---

## Part 1: Question Reordering

### Context

Assessments contain questions that are displayed in a specific order (the `sort_order` column on the `questions` table). Currently, the assessment edit page (`/assessments/{id}/edit`) displays questions in their sort order but provides **no mechanism to change the order**.

### Task

Add the ability to reorder questions within an assessment from the edit page.

### Requirements

- Users should be able to change the order of questions on an assessment
- The new order must be persisted to the database (updating `sort_order`)
- The UI should provide clear feedback about the current and updated order
- The reordered questions should appear in the new order everywhere they're displayed

### Implementation is up to you

You decide the UX approach. Some possibilities (not exhaustive):
- Drag-and-drop sortable list
- Up/down arrow buttons
- Inline number editing
- Something else entirely

You also decide the technical approach:
- Alpine.js interaction with an API endpoint
- Livewire component
- Standard form submission
- A combination

### What we're looking for

- Clean, working implementation
- Thoughtful UX that feels natural
- Proper data persistence
- Integration with the existing codebase patterns and styling

---

## Part 2: Assessment Versioning

### Context

Currently, editing an assessment (changing its title, description, status, or questions) directly mutates the record. If someone has already taken an assessment and you edit a question, the response data becomes disconnected from the original question text. There's no history, no way to know what version of the assessment a respondent actually saw.

### Task

Add versioning to assessments so that changes create or relate to a version rather than mutating in place.

### Requirements

- It should be possible to identify which "version" of an assessment a respondent took
- Editing an assessment should create a new version (or relate to one) rather than silently overwriting
- Existing responses should remain associated with the version they were collected under
- The UI should reflect versioning in some meaningful way (how is up to you)

### This is intentionally open-ended

There is no single correct architecture. You decide what "versioning" means:

- **Snapshot-based:** Freeze the current state as a version, create a new editable draft?
- **Copy-on-write:** Clone the assessment (and its questions) when editing, keep the old version immutable?
- **Version numbering:** Add a version number to assessments and track which version each response belongs to?
- **Change tracking:** Log individual changes as a version history?
- **Something else?**

Consider the trade-offs of your approach:
- How does it handle existing responses?
- What happens when you reorder questions (Part 1) — does that create a new version?
- How do you handle the relationship between versions and the "current" assessment?
- What does the data model look like? New tables? New columns?

### What we're looking for

- **Schema design**: How you model versions in the database. Clear migrations, appropriate relationships.
- **Architectural thinking**: How you reason about the trade-offs of your approach. We value a well-explained simple solution over a complex one.
- **Integration**: How versioning interacts with the existing features (taking assessments, viewing responses, the dashboard).
- **PR description**: Explain your approach, what trade-offs you considered, and what you'd do differently with more time.

---

## Submission

1. Create a branch: `candidate/<your-name>`
2. Implement both parts
3. Push your branch and open a PR to `main`
4. In your PR description, include:
   - A summary of your approach for each part
   - Any trade-offs or decisions you made and why
   - What you'd improve or do differently with more time
   - How long you spent (roughly)

There's no strict time limit, but we designed this to take **3-5 hours** for an experienced developer. Focus on quality over quantity — a well-implemented Part 1 with a thoughtful but incomplete Part 2 is better than a rushed implementation of both.

Good luck!
