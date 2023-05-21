<?php

declare(strict_types=1);

namespace Effectra\Fs;

/**
 * Utility class for converting files to data URLs and vice versa.
 */
class FileData
{
    /**
     * Convert a file to a data URL.
     *
     * @param string $file The path to the file.
     * @return string|false The data URL for the file, or false on failure.
     */
    public function fileToDataUrl(string $file): string|false
    {
        if (!File::isFile($file) || !File::isReadable($file)) {
            return false;
        }

        // Get the file contents 
        $contents = file_get_contents($file);
        if ($contents === false) {
            return false;
        }

        // Get the file base64 encode them
        $base64 = base64_encode($contents);
        if ($base64 === false) {
            return false;
        }

        // Determine the MIME type of the file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file);
        finfo_close($finfo);

        // Construct the data URL
        $dataUrl = "data:{$mime};base64,{$base64}";

        return $dataUrl;
    }

    /**
     * Convert a data URL to a file.
     *
     * @param string $dataUrl The data URL to convert.
     * @param string $file The path to save the file.
     * @return bool Whether the file was successfully saved.
     */
    public function dataUrlToFile(string $dataUrl, string $file): bool
    {
        $data = explode(',', $dataUrl, 2);
        if (count($data) !== 2) {
            return false;
        }

        $contents = base64_decode($data[1]);
        if ($contents === false) {
            return false;
        }

        $result = File::put($file, $contents);

        return ($result !== false);
    }
}
