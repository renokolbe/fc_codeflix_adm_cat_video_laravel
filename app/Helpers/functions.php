<?php

use Illuminate\Http\UploadedFile;

if (! function_exists('getArrayFile')) {

    function getArrayFile(?UploadedFile $file = null): ?array
    {
        if (! $file) {
            return null;
        }

        return [
            'name' => $file->getClientOriginalName(),
            'tmp_name' => $file->getPathname(),
            'error' => $file->getError(),
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }
}
