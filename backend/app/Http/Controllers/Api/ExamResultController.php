<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ExamResultController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access only.',
            ], 403);
        }

        $perPage = (int)$request->get('per_page', 10);
        $search = $request->get('search');
        $status = $request->get('status');
        $sortBy = $request->get('sort_by', 'date_added');
        $sortDir = strtolower($request->get('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $resultUserColumn = Schema::hasColumn('eis_exam_results', 'user_id')
            ? 'user_id'
            : (Schema::hasColumn('eis_exam_results', 'fk_employee_id') ? 'fk_employee_id' : null);

        $resultIdColumn = Schema::hasColumn('eis_exam_results', 'id')
            ? 'id'
            : (Schema::hasColumn('eis_exam_results', 'result_id') ? 'result_id' : $resultUserColumn);

        $scoreColumn = Schema::hasColumn('eis_exam_results', 'score')
            ? 'score'
            : (Schema::hasColumn('eis_exam_results', 'employee_score') ? 'employee_score' : null);

        $totalPointsColumn = Schema::hasColumn('eis_exam_results', 'total_points')
            ? 'total_points'
            : (Schema::hasColumn('eis_exam_results', 'total_item') ? 'total_item' : null);

        $finishColumn = Schema::hasColumn('eis_exam_results', 'finish_at')
            ? 'finish_at'
            : (Schema::hasColumn('eis_exam_results', 'submitted_at') ? 'submitted_at' : null);

        $createdColumn = Schema::hasColumn('eis_exam_results', 'created_at')
            ? 'created_at'
            : (Schema::hasColumn('eis_exam_results', 'taken_at')
            ? 'taken_at'
            : (Schema::hasColumn('eis_exam_results', 'started_at') ? 'started_at' : null));

        $examTopicColumn = Schema::hasColumn('eis_exams', 'fk_topic_id')
            ? 'fk_topic_id'
            : (Schema::hasColumn('eis_exams', 'topic_id')
            ? 'topic_id'
            : (Schema::hasColumn('eis_exams', 'category_id') ? 'category_id' : null));
        $passingRateColumn = Schema::hasColumn('eis_exams', 'passing_rate')
            ? 'passing_rate'
            : (Schema::hasColumn('eis_exams', 'passing_score') ? 'passing_score' : null);


        if ($resultUserColumn === null) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exam results.',
                'error' => 'No user foreign key column found on eis_exam_results.',
            ], 500);
        }

        $scoreExpr = $scoreColumn ? "COALESCE(r.{$scoreColumn}, 0)" : '0';
        $totalExpr = $totalPointsColumn ? "COALESCE(r.{$totalPointsColumn}, 100)" : '100';
        $finishNullExpr = $finishColumn ? "r.{$finishColumn} IS NULL" : '0=1';

        $passingRateExpr = $passingRateColumn ? "COALESCE(e.{$passingRateColumn}, 0)" : '0';

        $statusCase = "CASE WHEN {$finishNullExpr} THEN 'pending' WHEN {$scoreExpr} >= {$passingRateExpr} THEN 'passed' ELSE 'failed' END";

        $dateAddedSelect = $finishColumn && $createdColumn
            ?DB::raw("COALESCE(r.{$finishColumn}, r.{$createdColumn}) as date_added")
            : ($createdColumn
            ?DB::raw("r.{$createdColumn} as date_added")
            : ($finishColumn ?DB::raw("r.{$finishColumn} as date_added") : DB::raw('NULL as date_added')));

        $sortMap = [
            'exam_name' => 'e.title',
            'module_name' => $examTopicColumn ? 't.title' : 'e.title',
            'total_score' => $totalPointsColumn ? "r.{$totalPointsColumn}" : 'r.fk_exam_id',
            'employee_score' => $scoreColumn ? "r.{$scoreColumn}" : 'r.fk_exam_id',
            'passing_rate' => $passingRateColumn ? "e.{$passingRateColumn}" : 'e.id',
            'date_added' => $createdColumn ? "r.{$createdColumn}" : ($finishColumn ? "r.{$finishColumn}" : 'r.fk_exam_id'),
        ];

        try {
            $query = DB::table('eis_exam_results as r')
                ->join('eis_exams as e', 'e.id', '=', 'r.fk_exam_id')
                ->join('eis_users as u', 'u.id', '=', "r.{$resultUserColumn}");

            // Join with topic/category table based on which column exists
            if ($examTopicColumn) {
                if ($examTopicColumn === 'category_id') {
                    // category_id references eis_exam_categories
                    $query->leftJoin('eis_exam_categories as t', 't.id', '=', "e.{$examTopicColumn}");
                }
                else {
                    // fk_topic_id or topic_id references eis_topic
                    $query->leftJoin('eis_topic as t', 't.id', '=', "e.{$examTopicColumn}");
                }
            }

            $selects = [
                DB::raw("r.{$resultIdColumn} as result_id"),
                'e.id as exam_id',
                'e.title as exam_name',
                'r.fk_exam_id',
                DB::raw("r.{$resultUserColumn} as fk_employee_id"),
                DB::raw('COALESCE(u.name, u.email) as employee_username'),
                DB::raw('COALESCE(u.name, "Unknown") as employee_name'),
                'u.email as employee_email',
                DB::raw("{$totalExpr} as total_score"),
                DB::raw("{$scoreExpr} as employee_score"),
                DB::raw("{$passingRateExpr} as passing_rate"),
                DB::raw("$statusCase as result_status"),
                $dateAddedSelect,
            ];

            if ($examTopicColumn) {
                $selects[] = DB::raw('COALESCE(t.id, 0) as module_id');
                // Use name for categories, title for topics
                $titleColumn = $examTopicColumn === 'category_id' ? 'name' : 'title';
                $selects[] = DB::raw("COALESCE(t.{$titleColumn}, 'N/A') as module_name");
            }
            else {
                $selects[] = DB::raw('0 as module_id');
                $selects[] = DB::raw("'N/A' as module_name");
            }

            $query->select($selects);

            if (!empty($status) && in_array($status, ['passed', 'failed', 'pending'], true)) {
                $query->whereRaw("$statusCase = ?", [$status]);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search, $examTopicColumn, $scoreColumn, $totalPointsColumn) {
                    $q->orWhere('e.title', 'like', "%{$search}%")
                        ->orWhere('u.name', 'like', "%{$search}%")
                        ->orWhere('u.email', 'like', "%{$search}%");

                    if ($examTopicColumn) {
                        $q->orWhere('t.title', 'like', "%{$search}%");
                    }

                    if ($scoreColumn) {
                        $q->orWhere("r.{$scoreColumn}", 'like', "%{$search}%");
                    }

                    if ($totalPointsColumn) {
                        $q->orWhere("r.{$totalPointsColumn}", 'like', "%{$search}%");
                    }
                });
            }

            if ($sortBy === 'result_status') {
                $query->orderByRaw("$statusCase $sortDir");
            }
            else {
                $query->orderBy($sortMap[$sortBy] ?? ($createdColumn ? "r.{$createdColumn}" : 'r.fk_exam_id'), $sortDir);
            }

            return response()->json($query->paginate($perPage));
        }
        catch (\Exception $e) {
            Log::error('Error fetching exam results: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching exam results.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            // Because column names are unpredictable across forks, try to find the row flexibly.
            $resultUserColumn = Schema::hasColumn('eis_exam_results', 'user_id') ? 'user_id' : 'fk_employee_id';
            $resultIdColumn = Schema::hasColumn('eis_exam_results', 'id') ? 'id' : (Schema::hasColumn('eis_exam_results', 'result_id') ? 'result_id' : $resultUserColumn);

            $result = DB::table('eis_exam_results')->where($resultIdColumn, $id)->first();

            // If they mean fk_exam_id and fk_employee_id somehow? Let's check if $result exists.
            if (!is_object($result) && !is_array($result)) {
                return response()->json(['message' => 'Result not found.'], 404);
            }

            $examId = is_object($result) && isset($result->fk_exam_id) ? $result->fk_exam_id : (is_array($result) && isset($result['fk_exam_id']) ? $result['fk_exam_id'] : null);
            $exam = DB::table('eis_exams')->where('id', $examId)->first();

            if (!is_object($exam) && !is_array($exam)) {
                return response()->json(['message' => 'Exam not found.'], 404);
            }

            // Reconstruct questions & answer mapping
            $questionJsonVal = is_object($exam) && isset($exam->question_json) ? $exam->question_json : (is_object($exam) && isset($exam->questions) ? $exam->questions : '[]');
            $questions = is_string($questionJsonVal) ? json_decode($questionJsonVal, true) : [];

            $answersJsonColValue = is_object($exam) && isset($exam->answers_json) ? $exam->answers_json : (is_object($exam) && isset($exam->answer_json) ? $exam->answer_json : '[]');
            $correctAnswers = is_string($answersJsonColValue) ? json_decode($answersJsonColValue, true) : [];

            // Fetch employee answers
            $employeeAnswersRaw = is_object($result) && isset($result->employee_answer) ? $result->employee_answer : (is_object($result) && isset($result->answers_json) ? $result->answers_json : '[]');
            $employeeAnswers = is_string($employeeAnswersRaw) ? json_decode($employeeAnswersRaw, true) : [];

            $detailedQuestions = [];

            if (is_array($questions)) {
                foreach ($questions as $q) {
                    $qId = $q['id'] ?? null;
                    if (!$qId)
                        continue;

                    // Find actual correct answer
                    $correct = null;
                    if (is_array($correctAnswers)) {
                        foreach ($correctAnswers as $ca) {
                            if (($ca['question_id'] ?? null) == $qId) {
                                $correct = $ca['answer'] ?? null;
                                break;
                            }
                        }
                    }

                    // Find submitted answer
                    $submitted = null;
                    if (is_array($employeeAnswers)) {
                        foreach ($employeeAnswers as $ea) {
                            if (($ea['question_id'] ?? null) == $qId) {
                                $submitted = $ea['answer'] ?? null;
                                break;
                            }
                        }
                    }

                    $isCorrect = $this->isAnswerCorrect($submitted, $correct, $q['type'] ?? 'multiple');

                    $detailedQuestions[] = [
                        'id' => $qId,
                        'question' => $q['question'] ?? $q['text'] ?? 'Unknown Question',
                        'type' => $q['type'] ?? 'multiple',
                        'points' => $q['points'] ?? 1,
                        'options' => $q['options'] ?? [],
                        'employee_answer' => $submitted,
                        'is_correct' => $isCorrect,
                        // Not returning correct_answer explicitly!
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'exam_id' => $exam->id,
                    'title' => $exam->title,
                    'questions' => $detailedQuestions,
                ]
            ]);

        }
        catch (\Exception $e) {
            Log::error('Error fetching single exam result: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching single exam result.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal error',
            ], 500);
        }
    }

    private function isAnswerCorrect($submitted, $correct, string $type): bool
    {
        if ($submitted === null || $correct === null) {
            return false;
        }

        if ($type === 'multiple' || $type === 'true-false') {
            return (string)$submitted === (string)$correct;
        }

        if ($type === 'multiple-answer') {
            $submittedArr = is_array($submitted) ? $submitted : [$submitted];
            $correctArr = is_array($correct) ? $correct : [$correct];

            $submittedStrs = array_map('strval', $submittedArr);
            $correctStrs = array_map('strval', $correctArr);

            return count(array_diff($submittedStrs, $correctStrs)) === 0 && count(array_diff($correctStrs, $submittedStrs)) === 0;
        }

        if ($type === 'short') {
            return strtolower(trim((string)$submitted)) === strtolower(trim((string)$correct));
        }

        return false;
    }
}
