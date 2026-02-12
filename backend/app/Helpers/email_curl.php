<?php
$file_attachment = array();

function send_email($parameter = [], $success_message = '', $error_message = '') {
    if (!function_exists('curl_version')) return 'cURL is not enabled.';

    $mode = !empty($parameter['debug']) && $parameter['debug'] === true ? 'test_send_email' : 'send_email';
    $url = "https://proweaveremail.com/email/" . $mode;
    
    // Handle file attachment if provided
    if (isset($parameter['attachment']) && file_exists($parameter['attachment'])) {
        $attachmentPath = $parameter['attachment'];
        
        // Use custom filename if provided, otherwise use basename
        $fileName = $parameter['attachment_filename'] ?? basename($attachmentPath);
        
        \Log::info('Uploading attachment to ProWeaver API', [
            'file_name' => $fileName,
            'file_path' => $attachmentPath,
            'file_size' => filesize($attachmentPath),
        ]);
        
        // Upload file to ProWeaver file uploader endpoint first
        $uploadedFileUrl = upload_file_to_proweaver($attachmentPath, $fileName);
        
        if ($uploadedFileUrl) {
            // Add the uploaded file URL to attachments parameter
            $parameter['attachments'] = $uploadedFileUrl;
            \Log::info('File uploaded successfully', ['url' => $uploadedFileUrl]);
        } else {
            \Log::error('Failed to upload attachment to ProWeaver');
        }
        
        // Remove the file path and custom filename from parameters
        unset($parameter['attachment']);
        unset($parameter['attachment_filename']);
    }
    
    // Log email parameters (without sensitive content)
    \Log::info('Sending email via API', [
        'to' => $parameter['to'] ?? 'unknown',
        'from' => $parameter['from'] ?? 'unknown',
        'subject' => $parameter['subject'] ?? 'unknown',
        'has_attachment' => isset($parameter['attachments']),
        'attachment_url' => $parameter['attachments'] ?? null,
        'all_params' => array_keys($parameter),
        'url' => $url,
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameter));
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increase timeout for large attachments

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        \Log::error('Email cURL error', ['error' => $error]);
        return ['error' => $error];
    }

    curl_close($ch);
    
    // Log raw response
    \Log::info('Email API response', [
        'http_code' => $httpCode,
        'response' => $response,
    ]);

    if (!empty($parameter['debug']) && $parameter['debug'] === true) {

        $new_response = json_decode($response, true);

        $message = $new_response['message'] ?? null;

        return [
            // 'debug_raw' => $response,
            'raw' => $response,
            'decoded' => $message
        ];
    }

    $response = json_decode($response, true);
    if (isset($response['response']) && $response['response'] === 'sent') {
        \Log::info('Email sent successfully', ['to' => $parameter['to'] ?? 'unknown']);
        return $success_message ?: 'Email sent successfully';
    }
    
    \Log::warning('Email send failed', [
        'response' => $response,
        'to' => $parameter['to'] ?? 'unknown',
    ]);

    return $error_message ?: 'Failed to send email.';
}

/**
 * Upload file to ProWeaver file uploader endpoint
 * Returns the uploaded file URL or false on failure
 */
function upload_file_to_proweaver($filePath, $fileName) {
    try {
        $mime = get_mime_type($filePath);
        
        // Create CURLFile for file upload
        if (class_exists('CURLFile')) {
            $fileData = new \CURLFile($filePath, $mime, $fileName);
        } else {
            $fileData = "@$filePath;filename=$fileName;type=$mime";
        }
        
        $postFields = [
            'my_file' => $fileData,
        ];
        
        $url = "https://proweaveremail.com/email/file_uploader";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            \Log::error('File upload cURL error', ['error' => $error]);
            return false;
        }
        
        curl_close($ch);
        
        // Decode JSON response
        $result = json_decode($response, true);
        
        \Log::info('File upload response', [
            'http_code' => $httpCode,
            'response' => $response,
            'decoded' => $result,
        ]);
        
        // ProWeaver file uploader returns the file URL directly
        if ($result && is_string($result)) {
            return $result;
        } elseif ($result && isset($result['url'])) {
            return $result['url'];
        } elseif ($result && isset($result['file'])) {
            return $result['file'];
        }
        
        \Log::error('Unexpected file upload response format', ['response' => $result]);
        return false;
        
    } catch (\Exception $e) {
        \Log::error('File upload exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return false;
    }
}


function confirmation_email($parameter)
{
    if (!isset($parameter['to'], $parameter['message'], $parameter['from'])) {
        return false;
    }

    $confirmation_parameter = array(
        'email_address' => $parameter['to'],
        'from' => $parameter['from'],
        'message' => $parameter['message'],
    );

    $url = "http://proweaveremail.com/email/confirmation_email";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $confirmation_parameter);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $response;
}

function get_mime_type($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mime_types = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.ms-powerpoint',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    ];

    return $mime_types[$ext] ?? 'application/octet-stream';
}
