<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Stores uploads on the public disk and cleans up the file they replace.
 */
class PublicFileStore
{
    /**
     * Return the path that should be saved after an optional upload or removal.
     */
    public function replace(?string $currentPath, ?UploadedFile $upload, bool $remove, string $directory): ?string
    {
        if ($upload !== null) {
            $this->delete($currentPath);

            return $upload->store($directory, 'public');
        }

        if ($remove) {
            $this->delete($currentPath);

            return null;
        }

        return $currentPath;
    }

    /**
     * Delete a stored file when there is one.
     */
    public function delete(?string $path): void
    {
        if ($path !== null && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }
}
