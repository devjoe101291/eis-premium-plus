<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * GET /api/exams
     * Optional filters:
     * - ?q=keyword
     * - ?is_active=1|0 (maps to status)
     * - ?topic_id=# (alias for fk_topic_id)
     * - ?fk_topic_id=#
     * - ?with=topic
     * - ?per_page=15 (if you want pagination)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Exam::query();

        $with = $this->parseWith($request->query('with'));
        if (!empty($with)) {
            $query->with($with);
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('instructions', 'like', "%{$q}%");
            });
        }

        if ($request->filled('is_active')) {
            $isActive = filter_var($request->query('is_active'), FILTER_VALIDATE_BOOLEAN);
            $query->where('status', $isActive ? 0 : 1);
        }

        $topicId = $request->query('fk_topic_id', $request->query('topic_id'));
        if ($topicId !== null && $topicId !== '') {
            $query->where('fk_topic_id', (int) $topicId);
        }

        $query->latest('id');

        if ($request->filled('per_page')) {
            $perPage = max(1, min(100, (int) $request->query('per_page')));
            return response()->json($query->paginate($perPage));
        }

        return response()->json($query->get());
    }

    /**
     * POST /api/exams
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            // Accept topic_id from frontend; store in fk_topic_id (actual column)
            'topic_id'      => ['required', 'integer', 'exists:eis_topic,id'],
            'fk_topic_id'   => ['sometimes', 'integer', 'exists:eis_topic,id'],

            'title'         => ['required', 'string', 'max:255'],
            'instructions'  => ['nullable', 'string'],
            // Back-compat alias from some clients
            'description'   => ['nullable', 'string'],

            'passing_rate'  => ['nullable', 'numeric', 'min:0'],
            // Back-compat alias from some clients
            'passing_score' => ['nullable', 'numeric', 'min:0'],

            'time_limit'    => ['nullable', 'integer', 'min:0'],

            // Either provide status (0/1) or is_active (boolean). status wins if both provided.
            'status'        => ['nullable', 'integer', 'in:0,1'],
            'is_active'     => ['nullable', 'boolean'],

            'question_json' => ['nullable', 'array'],
            'answers_json'  => ['nullable', 'array'],
        ]);

        $data['fk_topic_id'] = isset($data['fk_topic_id']) ? (int) $data['fk_topic_id'] : (int) $data['topic_id'];
        unset($data['topic_id']);

        if (!array_key_exists('instructions', $data) && array_key_exists('description', $data)) {
            $data['instructions'] = $data['description'];
        }
        unset($data['description']);

        if (!array_key_exists('passing_rate', $data) && array_key_exists('passing_score', $data)) {
            $data['passing_rate'] = $data['passing_score'];
        }
        unset($data['passing_score']);

        if (array_key_exists('status', $data) && $data['status'] !== null) {
            $data['status'] = (int) $data['status'];
        } elseif (array_key_exists('is_active', $data) && $data['is_active'] !== null) {
            $data['status'] = $data['is_active'] ? 0 : 1;
        } else {
            $data['status'] = 0;
        }
        unset($data['is_active']);

        $exam = Exam::create($data);

        return response()->json([
            'message' => 'Exam created successfully',
            'data'    => $exam->load(['topic']),
        ], 201);
    }

    /**
     * GET /api/exams/{id}
     */
    public function show(Request $request, $id): JsonResponse
    {
        if (!ctype_digit((string) $id) || (int) $id <= 0) {
            return response()->json(['message' => 'Invalid exam id.'], 422);
        }

        $with = $this->parseWith($request->query('with'));
        $exam = Exam::query()
            ->when(!empty($with), fn ($q) => $q->with($with))
            ->findOrFail((int) $id);

        return response()->json([
            'message' => 'Exam fetched successfully',
            'data'    => $exam,
        ]);
    }

    /**
     * PUT/PATCH /api/exams/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $exam = Exam::findOrFail($id);

        $data = $request->validate([
            'fk_topic_id'   => ['sometimes', 'integer', 'exists:eis_topic,id'],
            // Alias support
            'topic_id'      => ['sometimes', 'integer', 'exists:eis_topic,id'],

            'title'         => ['sometimes', 'required', 'string', 'max:255'],
            'instructions'  => ['sometimes', 'nullable', 'string'],
            // Alias support
            'description'   => ['sometimes', 'nullable', 'string'],

            'passing_rate'  => ['sometimes', 'nullable', 'numeric', 'min:0'],
            // Alias support
            'passing_score' => ['sometimes', 'nullable', 'numeric', 'min:0'],

            'time_limit'    => ['sometimes', 'nullable', 'integer', 'min:0'],

            'status'        => ['sometimes', 'nullable', 'integer', 'in:0,1'],
            'is_active'     => ['sometimes', 'nullable', 'boolean'],

            'question_json' => ['sometimes', 'nullable', 'array'],
            'answers_json'  => ['sometimes', 'nullable', 'array'],
        ]);

        if (array_key_exists('topic_id', $data) && !array_key_exists('fk_topic_id', $data)) {
            $data['fk_topic_id'] = (int) $data['topic_id'];
        }
        unset($data['topic_id']);

        if (!array_key_exists('instructions', $data) && array_key_exists('description', $data)) {
            $data['instructions'] = $data['description'];
        }
        unset($data['description']);

        if (!array_key_exists('passing_rate', $data) && array_key_exists('passing_score', $data)) {
            $data['passing_rate'] = $data['passing_score'];
        }
        unset($data['passing_score']);

        if (!array_key_exists('status', $data) && array_key_exists('is_active', $data)) {
            $data['status'] = $data['is_active'] ? 0 : 1;
        }
        unset($data['is_active']);

        $exam->update($data);

        return response()->json([
            'message' => 'Exam updated successfully',
            'data'    => $exam->fresh()->load(['topic']),
        ]);
    }

    /**
     * DELETE /api/exams/{id}
     * Soft delete
     */
    public function destroy(int $id): JsonResponse
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json([
            'message' => 'Exam deleted successfully',
        ]);
    }

    /**
     * Optional: Restore soft-deleted exam
     * POST /api/exams/{id}/restore
     */
    public function restore(int $id): JsonResponse
    {
        $exam = Exam::onlyTrashed()->findOrFail($id);
        $exam->restore();

        return response()->json([
            'message' => 'Exam restored successfully',
            'data'    => $exam,
        ]);
    }

    /**
     * POST /api/exams/{id}/submit
     * Submit exam answers and calculate score
     */
    public function submit(Request $request, int $id): JsonResponse
    {
        $exam = Exam::findOrFail($id);

        $data = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'integer'],
            'answers.*.answer' => ['nullable'],
        ]);

        $answers = $data['answers'];
        $questionJson = is_array($exam->question_json) ? $exam->question_json : [];
        $answerJson = is_array($exam->answers_json) ? $exam->answers_json : (is_array($exam->answer_json) ? $exam->answer_json : []);

        // Calculate score
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questionJson as $question) {
            $questionId = $question['id'] ?? null;
            $questionPoints = $question['points'] ?? 1;
            $questionType = $question['type'] ?? 'multiple';

            if (!$questionId) continue;

            $totalPoints += $questionPoints;

            // Find correct answer for this question
            $correctAnswer = null;
            foreach ($answerJson as $answerRecord) {
                if (($answerRecord['question_id'] ?? null) == $questionId) {
                    $correctAnswer = $answerRecord['answer'] ?? null;
                    break;
                }
            }

            // Find submitted answer for this question
            $submittedAnswer = null;
            foreach ($answers as $answer) {
                if (($answer['question_id'] ?? null) == $questionId) {
                    $submittedAnswer = $answer['answer'] ?? null;
                    break;
                }
            }

            // Check if answer is correct
            $isCorrect = $this->isAnswerCorrect($submittedAnswer, $correctAnswer, $questionType);
            if ($isCorrect) {
                $earnedPoints += $questionPoints;
            }
        }

        // Calculate percentage score
        $scorePercentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $isPassed = $scorePercentage >= ($exam->passing_rate ?? 0);

        // Store result
        $userId = $request->user()?->id;
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $result = \App\Models\ExamResult::create([
            'user_id' => $userId,
            'fk_exam_id' => $id,
            'score' => round($scorePercentage, 2),
            'total_points' => $totalPoints,
            'employee_answer' => json_encode($answers),
            'taken_at' => now(),
            'finish_at' => now(),
        ]);

        return response()->json([
            'message' => 'Exam submitted successfully',
            'data' => [
                'id' => $result->id,
                'exam_result_id' => $result->id,
                'score' => round($scorePercentage, 2),
                'passed' => $isPassed,
                'earned_points' => $earnedPoints,
                'total_points' => $totalPoints,
                'passing_rate' => $exam->passing_rate,
            ],
        ], 201);
    }

    /**
     * Check if submitted answer matches correct answer
     */
    private function isAnswerCorrect($submitted, $correct, string $type): bool
    {
        if ($submitted === null || $correct === null) {
            return false;
        }

        if ($type === 'multiple' || $type === 'true-false') {
            return (string) $submitted === (string) $correct;
        }

        if ($type === 'multiple-answer') {
            $submittedArr = is_array($submitted) ? $submitted : [$submitted];
            $correctArr = is_array($correct) ? $correct : [$correct];

            $submitted = array_map('strval', $submittedArr);
            $correct = array_map('strval', $correctArr);

            return count(array_diff($submitted, $correct)) === 0 && count(array_diff($correct, $submitted)) === 0;
        }

        if ($type === 'short') {
            // For short answers, do case-insensitive comparison
            return strtolower(trim((string) $submitted)) === strtolower(trim((string) $correct));
        }

        return false;
    }

    private function parseWith(?string $with): array
    {
        if (!$with) return [];

        $allowed = ['topic'];
        $requested = array_filter(array_map('trim', explode(',', $with)));

        return array_values(array_intersect($allowed, $requested));
    }
}

