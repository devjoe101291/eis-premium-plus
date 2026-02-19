<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access only.',
            ], 403);
        }

        $template = $this->getActiveTemplate();
        $content = $this->decodeContent($template->content);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $template->id,
                'template_name' => $template->template_name,
                'content' => $content,
                'e_signature_url' => $this->signatureUrl($template->e_signature),
                'is_active' => (bool) $template->is_active,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access only.',
            ], 403);
        }

        $validated = $request->validate([
            'organizationName' => 'required|string|max:255',
            'programName' => 'required|string|max:255',
            'sampleRecipientName' => 'required|string|max:255',
            'certificateText' => 'required|string',
            'leftSignatureName' => 'nullable|string|max:255',
            'leftSignaturePosition' => 'nullable|string|max:255',
            'rightSignatureName' => 'nullable|string|max:255',
            'rightSignaturePosition' => 'nullable|string|max:255',
            'right_signature_data' => 'nullable|string',
            'dateISO' => 'required|date',
            'e_signature_data' => 'nullable|string',
            'remove_signature' => 'nullable|boolean',
        ]);

        $contentPayload = [
            'organizationName' => $validated['organizationName'],
            'programName' => $validated['programName'],
            'sampleRecipientName' => $validated['sampleRecipientName'],
            'certificateText' => $validated['certificateText'],
            'leftSignatureName' => $validated['leftSignatureName'] ?? '',
            'leftSignaturePosition' => $validated['leftSignaturePosition'] ?? '',
            'rightSignatureName' => $validated['rightSignatureName'] ?? '',
            'rightSignaturePosition' => $validated['rightSignaturePosition'] ?? '',
            'rightSignatureDataUrl' => $validated['right_signature_data'] ?? '',
            'dateISO' => $validated['dateISO'],
        ];

        try {
            $template = $this->getActiveTemplate();
            $template->template_name = $template->template_name ?: 'Default';
            $template->content = json_encode($contentPayload, JSON_UNESCAPED_SLASHES);
            $template->is_active = true;

            $removeSignature = filter_var($request->input('remove_signature', false), FILTER_VALIDATE_BOOLEAN);

            if ($request->filled('e_signature_data')) {
                if (!empty($template->e_signature) && !$this->isDataSignature($template->e_signature) && Storage::disk('public')->exists($template->e_signature)) {
                    Storage::disk('public')->delete($template->e_signature);
                }
                $template->e_signature = $request->input('e_signature_data');
            } elseif ($removeSignature) {
                if (!empty($template->e_signature) && !$this->isDataSignature($template->e_signature) && Storage::disk('public')->exists($template->e_signature)) {
                    Storage::disk('public')->delete($template->e_signature);
                }
                $template->e_signature = null;
            }

            $template->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $template->id,
                    'template_name' => $template->template_name,
                    'content' => $this->decodeContent($template->content),
                    'e_signature_url' => $this->signatureUrl($template->e_signature),
                    'is_active' => (bool) $template->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving certificate settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving certificate settings.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    private function getActiveTemplate(): CertificateTemplate
    {
        $template = CertificateTemplate::where('is_active', true)->first();
        if ($template) {
            return $template;
        }

        $defaults = $this->defaultContent();

        return CertificateTemplate::create([
            'template_name' => 'Default',
            'content' => json_encode($defaults, JSON_UNESCAPED_SLASHES),
            'is_active' => true,
        ]);
    }

    private function decodeContent(?string $content): array
    {
        if (!$content) {
            return $this->defaultContent();
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return $this->defaultContent();
        }

        return array_merge($this->defaultContent(), $decoded);
    }

    private function signatureUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if ($this->isDataSignature($path) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::url($path);
    }

    private function isDataSignature(string $value): bool
    {
        return str_starts_with($value, 'data:image/');
    }

    private function defaultContent(): array
    {
        return [
            'organizationName' => 'Proweaver, Inc',
            'programName' => 'Online Examination Program',
            'sampleRecipientName' => 'John Doe',
            'certificateText' => 'This certificate is awarded to this individual for the successful completion of the online examination program.',
            'leftSignatureName' => 'Test Admin',
            'leftSignaturePosition' => 'AdminTest',
            'rightSignatureName' => '',
            'rightSignaturePosition' => '',
            'rightSignatureDataUrl' => '',
            'dateISO' => now()->toDateString(),
        ];
    }
}










