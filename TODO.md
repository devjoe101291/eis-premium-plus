# EIS Exams Schema Fix & Cleanup — COMPLETED ✅

## Tasks

- [x] 1. Create new migration: add `question_json`, `answers_json`, `topic_id`, `category` to `eis_exams`; drop `category_id`; drop legacy tables
- [x] 2. Update `Exam.php` model: fillable, casts, topic() relationship
- [x] 3. Update `ExamController.php`: validation, store(), update(), index()
- [x] 4. Update `frontend/src/config/types/exam.ts`: replace `category_id` with `category`
- [x] 5. Update `frontend/src/views/ExamCreatorView.vue`: exam ref, loadExam, buildPayloadForController
- [x] 6. Run `php artisan migrate` — migration `2026_03_03_000001` applied (38ms)
- [x] 7. Fix: create migration `2026_03_03_063931` to make `exam_type`, `description`, `passing_criteria_type`, `created_by`, `passing_score`, `time_limit` nullable — applied (185ms)
- [x] 8. Thorough API testing — all 8 tests PASS (POST/GET/PUT/DELETE /api/exams)

---

# TopicDetail Exam Table — Instructions & Passing Rate Fix — COMPLETED ✅

## Root Cause
3-layer naming mismatch between DB columns, Eloquent model, and controller:

| Layer | Instructions field | Passing Rate field | Active/Status field |
|---|---|---|---|
| **DB column** | `instructions` | `passing_rate` | `status` (tinyInt 0/1) |
| **Model (before fix)** | `description` ❌ | `passing_score` ❌ | `is_active` ❌ |
| **Controller (before fix)** | `description` ❌ | `passing_score` ❌ | `is_active` ❌ |
| **Frontend ExamCreator sends** | `instructions` ✅ | `passing_rate` ✅ | `is_active` (bool) |
| **Frontend TopicDetail reads** | `e.instructions` ✅ | `e.passing_rate` ✅ | `e.status` / `e.is_active` |

## Actual DB Columns (discovered via `SHOW COLUMNS FROM eis_exams`)

The real database had **different column names** than the original migration file suggested:

| Column | Actual DB | Original Migration |
|---|---|---|
| Instructions | `description` | `instructions` |
| Passing Rate | `passing_score` | `passing_rate` |
| Active/Status | `is_active` (tinyint, 1=Active) | `status` (tinyint, 0=Active) |

## Tasks

- [x] 1. Fix `backend/app/Models/Exam.php`:
  - `$fillable`: `description` → `instructions`, `passing_score` → `passing_rate`, `is_active` → `status`; added `fk_topic_id`
  - `$casts`: `passing_score` → `passing_rate` (decimal:2), `is_active` → `status` (integer); added `fk_topic_id`
- [x] 2. Fix `backend/app/Http/Controllers/Api/ExamController.php`:
  - `store()` & `update()`: validation `description` → `instructions`, `passing_score` → `passing_rate`
  - `store()` & `update()`: map `is_active` (boolean) → `status` (0=Active, 1=Inactive) before saving
  - `index()`: filter query changed from `where('is_active', ...)` to `where('status', ...)`
- [x] 3. Create DB migration `2026_03_03_082409_rename_exam_columns_to_match_api_in_eis_exams_table.php`:
  - Renames `description` → `instructions`
  - Renames `passing_score` → `passing_rate`
  - Renames `is_active` → `status` + inverts values (1→0 for active, 0→1 for inactive) to match 0=Active convention
  - Migration applied successfully (84ms)
- [x] 4. Fix `frontend/src/views/TopicDetail.vue`:
  - `topicExams` computed: status filter changed from `!!e.is_active` / `!e.is_active` to `isExamActive(e)` / `!isExamActive(e)`
  - This ensures the Active/Inactive filter works correctly with the new `status` integer field (0=Active, 1=Inactive)

## Test Results

| Test | Result |
|---|---|
| Auth token (Sanctum) | ✅ PASS |
| Fetch valid topic_id (id=2) | ✅ PASS |
| POST /api/exams with `instructions` + `passing_rate` | ✅ PASS — 201 Created, fields saved correctly |
| GET /api/exams?topic_id=2 — `instructions` & `passing_rate` returned | ✅ PASS — 200 OK |
| PUT /api/exams/{id} — update `instructions` & `passing_rate` | ✅ PASS — 200 OK, fields updated correctly |
| DB columns after migration | ✅ `instructions`, `passing_rate`, `status` confirmed via SHOW COLUMNS |
