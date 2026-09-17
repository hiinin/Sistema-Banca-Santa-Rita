<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadService
{
    /**
     * Upload an image file securely to the public disk.
     */
    public function uploadImage(UploadedFile $file, string $directory = 'contents'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.strtolower($extension);

        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Upload a video file securely to the public disk.
     */
    public function uploadVideo(UploadedFile $file, string $directory = 'videos'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.strtolower($extension);

        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Delete an existing media file from storage to prevent orphan files.
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Never delete demo SVGs or external URLs
        if (str_starts_with($path, 'images/') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
