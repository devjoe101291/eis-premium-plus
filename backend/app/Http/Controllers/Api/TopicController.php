<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * GET /topics
     */
  public function index()
{
    return response()->json(
        Topic::with('creator') // ✅ load user
            ->orderBy('created_at', 'desc')
            ->get()
    );
}




    /**
     * POST /topics
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $topic = Topic::create([
            ...$validated,
            'created_by' => Auth::id(), // ✅ auto-assign creator
        ]);

        return response()->json($topic, 201);
    }

    /**
     * GET /topics/{id}
     */
    public function show($id)
    {
        return response()->json(
            Topic::findOrFail($id)
        );
    }

    /**
     * PUT /topics/{id}
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $topic->update($validated);

        return response()->json($topic);
    }

    /**
     * DELETE /topics/{id}
     */
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();

        return response()->json([
            'message' => 'Topic deleted successfully'
        ]);
    }
}
