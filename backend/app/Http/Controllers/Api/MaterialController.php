<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // GET /api/materials
    // Optional: ?topic_id=1 to filter materials by topic
// GET /api/materials?topic_id=1
public function index(Request $request)
{
    $query = Material::query();

    if ($request->filled('topic_id')) {
        $query->where('topic_id', $request->topic_id);
    }

    return response()->json(
        $query->latest()->get()
    );
}


    // POST /api/materials
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'topic_id'     => 'required|exists:eis_topic,id',   // ✅ new
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'file_type'    => 'required|in:file,url',
            'file'         => 'required_if:file_type,file|file',
            'video_link'   => 'required_if:file_type,url|nullable|url',
            'is_active'    => 'required|boolean',
        ]);

        $path = null;

        if ($data['file_type'] === 'file' && $request->hasFile('file')) {
            $path = $request->file('file')->store('materials', 'public');
        }

        $material = Material::create([
            'topic_id'     => $data['topic_id'], // ✅ new
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'file_type'    => $data['file_type'],
            'file_path'    => $path,
            'video_link'   => $data['file_type'] === 'url' ? $data['video_link'] : null,
            'is_active'    => $data['is_active'],
        ]);

        return response()->json($material, 201);
    }

public function show($id){
    \Log::info('Fetching material', ['id' => $id]);

    $material = Material::find($id);

    \Log::info('Material result', ['material' => $material]);

    if (!$material) {
        return response()->json(['message' => 'Material not found'], 404);
    }

    return response()->json($material);
}

    // Hard delete (force)
    public function destroy($id)
    {
        $material = Material::withTrashed()->findOrFail($id);
        $material->forceDelete();

        return response()->json(['message' => 'Permanently deleted']);
    }

    // Soft delete
    public function softDelete($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Optional: stats (global or per topic)
    public function stats(Request $request): JsonResponse
    {
        $query = Material::query();

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        return response()->json([
            'total'    => (clone $query)->count(),
            'active'   => (clone $query)->where('is_active', 1)->count(),
            'inactive' => (clone $query)->where('is_active', 0)->count(),
        ]);
    }
public function download($id)
{
    $material = Material::findOrFail($id);
    $relativePath = $material->file_path;

    if (!Storage::disk('public')->exists($relativePath)) {
        abort(404, 'File not found.');
    }

    return response()->download(
        Storage::disk('public')->path($relativePath),
        basename($relativePath)
    );
}

    // Update material
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $request->validate([
            'topic_id'   => 'required|exists:eis_topic,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'file_type'  => 'required|in:file,url',
            'file'       => 'sometimes|file',
            'video_link' => 'required_if:file_type,url|nullable|url',
            'is_active'  => 'required|boolean',
        ]);

        // handle file upload if provided
        if ($request->hasFile('file') && $data['file_type'] === 'file') {
            // delete old file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $path = $request->file('file')->store('materials', 'public');
            $material->file_path = $path;
            $material->video_link = null;
        } else {
            // if switching to url type, clear file_path
            if ($data['file_type'] === 'url') {
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
                $material->file_path = null;
                $material->video_link = $data['video_link'] ?? null;
            }
        }

        $material->topic_id = $data['topic_id'];
        $material->title = $data['title'];
        $material->description = $data['description'] ?? null;
        $material->file_type = $data['file_type'];
        if ($data['file_type'] === 'url') {
            $material->video_link = $data['video_link'] ?? null;
        }
        $material->is_active = $data['is_active'];

        $material->save();

        return response()->json($material);
    }

}

