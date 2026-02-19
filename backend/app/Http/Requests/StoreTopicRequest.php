<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Set to true if any authenticated user can add a topic
        return true;
    }

    public function rules(): array
    {
        return [
            'lesson_id' => 'required|exists:fhc_lessons,lesson_id',
            'topic_title' => 'required|string|max:255',
            'topic_description' => 'nullable|string',
            'week_number' => 'required|integer|min:1',
            'status' => 'required|in:Draft,Published,Archived',
        ];
    }
}
