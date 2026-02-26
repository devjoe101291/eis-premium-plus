<?php
use Illuminate\Support\Facades\DB;

$results = DB::table('eis_exam_results')->get();

foreach ($results as $r) {
    if (is_null($r->fk_employee_id) || is_null($r->total_score) || is_null($r->result_status)) {

        $exam = DB::table('eis_exams')->where('id', $r->fk_exam_id)->first();
        $passing_rate = $exam->passing_rate ?? 0;

        // Compute correct score percentage
        $total = $r->total_points ?: 1;
        $score = $r->score ?? $r->employee_score ?? 0;

        $percentage = ($score / $total) * 100;
        $status = $percentage >= $passing_rate ? 'passed' : 'failed';

        DB::table('eis_exam_results')
            ->where('result_id', $r->result_id)
            ->update([
            'fk_employee_id' => $r->user_id ?? $r->fk_employee_id,
            'employee_score' => $score,
            'total_score' => $total,
            'passing_rate' => $passing_rate,
            'result_status' => $status
        ]);
    }
}
echo "Cleaned up historically incomplete rows successfully! \n";
