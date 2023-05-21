<?php

declare(strict_types=1);

namespace Effectra\Fs\Type;

use Effectra\Fs\File;

/**
 * Class Csv
 *
 * Provides utility methods for working with csv files.
 */
class Csv
{
    /**
     * Check if a file is a CSV file.
     *
     * @param string $file The path to the file.
     * @return bool Whether the file is a CSV file.
     */
    public static function isCsv(string $file): bool
    {
        return File::mimeType($file) === 'application/csv';
    }

    /**
     * Read and parse a CSV file.
     *
     * @param string $file The path to the CSV file.
     * @return array|false An array of CSV data, or false on failure.
     */
    public static function read(string $file): array|false
    {
        if (!File::exists($file)) {
            return false;
        }

        $csvArray = [];
        $csvToRead = fopen($file, 'r');

        while (($row = fgetcsv($csvToRead, 0, ',')) !== false) {
            $csvArray[] = $row;
        }

        fclose($csvToRead);

        return $csvArray;
    }

    /**
     * Create a CSV file and write data to it.
     *
     * @param string $path The path to the CSV file.
     * @param array $data The data to be written to the CSV file.
     * @return void
     */
    public static function create(string $path, array $data): void
    {
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'csv') {
            $path .= '.csv';
        }

        $csvToWrite = fopen($path, 'w');

        foreach ($data as $fields) {
            fputcsv($csvToWrite, $fields);
        }

        fclose($csvToWrite);
    }
    /**
     * Edit a CSV file by replacing its contents with new data.
     *
     * @param string $file The path to the CSV file to edit.
     * @param array $data The new data to be written to the CSV file.
     * @return bool Whether the CSV file was successfully edited.
     */
    public static function edit(string $file, array $data): bool
    {
        if (!static::isCsv($file)) {
            return false;
        }

        $csvToWrite = fopen($file, 'w');

        if (!$csvToWrite) {
            return false;
        }

        foreach ($data as $fields) {
            fputcsv($csvToWrite, $fields);
        }

        fclose($csvToWrite);

        return true;
    }

    /**
     * Retrieves the first line of a CSV file and returns it as an array.
     *
     * @param string $filePath The path to the CSV file.
     * @param string $delimiter (Optional) The delimiter used in the CSV file. Defaults to comma (,).
     * @return array|false The first line of the CSV file as an array, or false on failure.
     */
    public static function getFirstLine(string $filePath, string $delimiter = ','): array|false
    {
        if (!static::isCsv($filePath)) {
            return false;
        }
        $handle = fopen($filePath, 'r');

        if ($handle !== false) {
            $firstLine = fgets($handle);
            fclose($handle);

            // Remove any trailing newline or carriage return characters
            $firstLine = rtrim($firstLine, "\r\n");

            // Split the line using the specified delimiter
            $dataArray = explode($delimiter, $firstLine);

            return $dataArray;
        } else {
            return false; // Failed to open the file
        }
    }
    /**
     * Retrieves the number of lines in a CSV file, excluding the first line.
     *
     * @param string $filePath The path to the CSV file.
     * @param string $delimiter `Optional` The delimiter used in the CSV file. Defaults to comma `,`.
     * @return int|false The number of lines in the CSV file, excluding the first line, or false on failure.
     */
    public static function getNumberOfLines($filePath, $delimiter = ','): int|false
    {
        if (!static::isCsv($filePath)) {
            return false;
        }
        $handle = fopen($filePath, 'r');

        if ($handle !== false) {
            $columnCount = null;
            $skipFirstLine = true;

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                if ($skipFirstLine) {
                    $skipFirstLine = false;
                    continue;
                }

                $currentColumnCount = count($data);

                if ($columnCount === null) {
                    $columnCount = $currentColumnCount;
                } elseif ($currentColumnCount !== $columnCount) {
                    fclose($handle);
                    return false; // Inconsistent number of columns
                }
            }

            fclose($handle);

            return $columnCount;
        } else {
            return false; // Failed to open the file
        }
    }
}
