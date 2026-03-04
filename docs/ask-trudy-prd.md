# PRD: Ask Trudy — LLM Assessment Coach

**Status:** Complete
**Date:** 2026-03-04
**Branch:** `feature/ask-trudy`

---

## Overview

Ask Trudy is an AI-powered chat assistant embedded in the TrueSpace interview app. Trudy helps authenticated users understand their own assessment results through natural-language conversation. She is scoped strictly to the requesting user's data — she cannot see or reveal another user's responses.

---

## Requirements

| # | Requirement |
|---|---|
| 1 | Users can access a chat page via the "Ask Trudy" nav link |
| 2 | Trudy is powered by OpenAI GPT-4o |
| 3 | Trudy's context includes only the authenticated user's assessment responses |
| 4 | Trudy cannot access or reveal any other user's data |
| 5 | Chat history persists across sessions (stored in the database per user) |
| 6 | Subsequent messages include full conversation history for multi-turn context |
| 7 | The OpenAI API key is stored securely in `.env` (gitignored) |
| 8 | Trudy greets new users on first visit with a welcoming message |

---

## Architecture

### LLM Provider
- **Package:** `openai-php/laravel` (v0.11.0)
- **Model:** `gpt-4o`
- **Config:** `config/openai.php` — reads `OPENAI_API_KEY` from environment
- **API key storage:** `.env` file (already in `.gitignore`); stub added to `.env.example`

### System Prompt Strategy

Trudy's system prompt is rebuilt on every message send. It includes:

1. **Persona instructions** — friendly, concise, professional; never invent data
2. **Scoping rules** — only discuss data in the prompt; decline other users' data requests
3. **Interpretation guidance** — give context for numeric (1-10) and Likert-scale scores
4. **User's assessment data** — all of the user's responses, grouped by assessment, formatted as:

```
Assessment: Leadership Effectiveness (status: active)
  Q: How would you rate your direct manager's communication skills?
  A: 8
  Q: My manager clearly communicates team goals and expectations.
  A: Agree
  ...
```

If the user has no responses, the prompt notes that so Trudy can encourage them to take an assessment.

### Conversation History

Full conversation history is sent to OpenAI on every message as the `messages` array, enabling true multi-turn context. The flow per message:

```
[system prompt] + [all prior messages] + [new user message]
    → OpenAI GPT-4o
    → assistant reply
```

---

## Data Model

### New Table: `chat_messages`

| Column | Type | Notes |
|---|---|---|
| `id` | bigIncrements | PK |
| `user_id` | foreignId | FK → users, cascade delete |
| `role` | enum('user','assistant') | Who sent the message |
| `content` | text | Message body |
| `created_at` | timestamp | Insertion order = conversation order |
| `updated_at` | timestamp | |

---

## Files Changed

### New Files

| File | Purpose |
|---|---|
| `database/migrations/..._create_chat_messages_table.php` | Creates `chat_messages` table |
| `app/Models/ChatMessage.php` | Eloquent model; `fillable`, `belongsTo(User)` |
| `app/Livewire/AskTrudy.php` | Livewire component — loads history, sends messages, builds system prompt |
| `resources/views/livewire/ask-trudy.blade.php` | Chat widget HTML (no layout wrapper — rendered as an embedded component) |
| `resources/views/ask-trudy.blade.php` | Page wrapper — `<x-app-layout>` + `<livewire:ask-trudy />` |
| `docs/ask-trudy-prd.md` | This document |
| `config/openai.php` | Published by `openai-php/laravel` |

### Modified Files

| File | Change |
|---|---|
| `composer.json` / `composer.lock` | Added `openai-php/laravel` |
| `app/Models/User.php` | Added `chatMessages()` hasMany relationship |
| `routes/web.php` | Added `GET /ask-trudy` as a view closure returning `ask-trudy` page view |
| `resources/views/layouts/navigation.blade.php` | Added "Ask Trudy" nav link (desktop + mobile) |
| `.env.example` | Added `OPENAI_API_KEY=` stub |

### Routing Note

Livewire 3 full-page components (route → Livewire class directly) require a layout at `components/layouts/app.blade.php`, which this project does not use. Instead, the route returns a standard Blade view (`ask-trudy.blade.php`) that wraps the component with `<x-app-layout>` — consistent with how all other routes in this project work.

---

## Security

| Concern | Mitigation |
|---|---|
| API key exposure | Stored in `.env` (gitignored); `.env.example` has empty stub only |
| Cross-user data access | Assessment data fetched with `where('user_id', auth()->id())` — server-side scope |
| Chat history cross-access | Messages fetched with `where('user_id', auth()->id())` |
| Prompt injection | User input is passed as a `user` role message, not interpolated into the system prompt |

---

## Out of Scope

- Streaming responses (SSE/WebSockets)
- Admin visibility into user conversations
- Clearing/resetting chat history from the UI
- Multiple conversation threads per user
- Trudy answering questions outside of assessment data

---

## Verification

1. Add `OPENAI_API_KEY=sk-...` to `.env`
2. Run `docker compose exec app php artisan migrate`
3. Log in as any user → click "Ask Trudy" in nav → see Trudy's greeting
4. Ask: "Tell me about my assessment scores" → Trudy summarizes that user's data
5. Refresh page → conversation history is preserved
6. Log in as a different user → see a separate, empty conversation (or their own history)
7. Ask Trudy about another user → she politely declines
