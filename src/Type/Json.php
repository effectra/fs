<?php

declare(strict_types=1);

namespace Effectra\Fs\Type;

use Effectra\Fs\File;

/**
 * Class Json
 *
 * Provides utility methods for working with JSON files.
 */
class Json
{
    /**
     * Checks if a file is of JSON type.
     *
     * @param string $file The path to the file.
     * @return bool Whether the file is of JSON type.
     */
    public static function isJson($file)
    {
        return File::mimeType($file) == 'application/json';
    }

    /**
     * Encodes a value into a JSON string.
     *
     * @param mixed $value The value to encode.
     * @param int $flags (Optional) Bitmask of JSON encode options. Defaults to 0.
     * @param int $depth (Optional) The maximum depth to traverse. Defaults to 512.
     * @return string|false The JSON string on success, or false on failure.
     */
    public static function encode(mixed $value, int $flags = 0, int $depth = 512): string|false
    {
        return json_encode(...func_get_args());
    }

    /**
     * Decodes a JSON string into a PHP value.
     *
     * @param string $path The path to the JSON file.
     * @param bool|null $associative (Optional) When true, the function returns associative arrays. When false, it returns objects. Defaults to null.
     * @param int $depth (Optional) The maximum depth to traverse. Defaults to 512.
     * @param int $flags (Optional) Bitmask of JSON decode options. Defaults to 0.
     * @return mixed The decoded value on success, or null on failure.
     */
    public static function decode(string $path, ?bool $associative = null, int $depth = 512, int $flags = 0): mixed
    {
        if (File::isReadable($path) && static::isJson($path)) {
            $json = File::getContent($path, null);
            return json_decode($json, $associative, $depth, $flags);
        }
        return null;
    }

    /**
     * Decodes a JSON file as an associative array.
     *
     * @param string $path The path to the JSON file.
     * @return array|null The decoded associative array on success, or null on failure.
     */
    public static function decodeAsArray(string $path): array|null
    {
        return static::decode($path, true);
    }

    /**
     * Decodes a JSON file as a stdClass object.
     *
     * @param string $path The path to the JSON file.
     * @return stdClass|null The decoded stdClass object on success, or null on failure.
     */
    public static function decodeAsStdClass(string $path): \stdClass|null
    {
        return static::decode($path);
    }

    /**
     * Checks if a JSON file is empty.
     *
     * @param string $file The path to the JSON file.
     * @return bool Whether the JSON file is empty.
     */
    public static function isEmpty($file)
    {
        return count(static::decodeAsArray($file) ?? 0) !== 0;
    }

    /**
     * Retrieves the last JSON error code.
     *
     * @return int The last JSON error code.
     */
    public static function lastError(): int
    {
        return json_last_error();
    }

    /**
     * Retrieves the last JSON error message.
     *
     * @return string The last JSON error message.
     */
    public static function lastErrorMessage(): string
    {
        return json_last_error_msg();
    }

    /**
     * Creates a JSON file with the given data.
     *
     * @param string $path The path to the JSON file.
     * @param mixed $data The data to be encoded and stored in the JSON file.
     * @param int $flags (Optional) Bitmask of JSON encode options. Defaults to 0.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function create($path, $data, int $flags = 0): int|false
    {
        if (strpos($path, '.json') === false) {
            $path .= '.json';
        }
        if (is_array($data)) {
            $data = static::encode($data, $flags);
        }
        return File::put($path, $data);
    }

    /**
     * Creates a JSON file with the given data and applies pretty printing.
     *
     * @param string $path The path to the JSON file.
     * @param mixed $data The data to be encoded and stored in the JSON file.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function createPretty($path, $data): int|false
    {
        return static::create($path, $data, JSON_PRETTY_PRINT);
    }

    /**
     * Creates a JSON file with the given data and handles errors related to UTF-8 encoding.
     *
     * @param string $path The path to the JSON file.
     * @param mixed $data The data to be encoded and stored in the JSON file.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function createErrorUTF8($path, $data): int|false
    {
        return static::create($path, $data, JSON_ERROR_UTF8);
    }


    /**
     * Retrieves a piece of data from a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @param int $length The length of data to retrieve.
     * @return string The retrieved piece of data.
     */
    public static function getPieceOfData($file, $length)
    {
        return str_replace(' ', '', File::getContent($file, $length));
    }

    /**
     * Checks if the data in a JSON file represents an array.
     *
     * @param string $file The path to the JSON file.
     * @return bool Whether the data represents an array.
     */
    public static function dataIsArray($file)
    {
        $data = static::getPieceOfData($file, 20);
        return $data[0] === '[' ? true : false;
    }


    /**
     * Checks if the data in a JSON file represents an object.
     *
     * @param string $file The path to the JSON file.
     * @return bool Whether the data represents an object.
     */
    public static function dataIsObject($file)
    {
        $data = static::getPieceOfData($file, 20);
        return $data[0] === '{' ? true : false;
    }

    /**
     * Modifies an array stored in a JSON file using a callback function.
     *
     * @param string $file The path to the JSON file.
     * @param mixed $data Additional data to be passed to the callback function.
     * @param callable $callback The callback function to modify the array.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function putInArray($file, $data, callable $callback)
    {
        if (!static::dataIsArray($file)) {
            return false;
        }

        $fileData = static::decode($file);

        $newData = call_user_func_array($callback, [$fileData, $data]);

        return File::put($file, static::encode($newData));
    }


    /**
     * Adds an element to the end of an array stored in a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @param mixed $data The element to be added to the array.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function push($file, $data)
    {
        return static::putInArray($file, $data, static function ($fileData, $data) {
            array_push($fileData, $data);
            return $fileData;
        });
    }

    /**
     * Removes the first element from an array stored in a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function shift($file)
    {
        return static::putInArray($file, null, static function ($fileData) {
            array_shift($fileData);
            return $fileData;
        });
    }


    /**
     * Removes the last element from an array stored in a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function pop($file)
    {
        return static::putInArray($file, null, static function ($fileData) {
            array_pop($fileData);
            return $fileData;
        });
    }

    /**
     * Retrieves the keys of an array stored in a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @return array The keys of the array.
     */
    public static function keys($file)
    {
        return array_keys(static::decodeAsArray($file));
    }

    /**
     * Retrieves the values of an array stored in a JSON file.
     *
     * @param string $file The path to the JSON file.
     * @return array The values of the array.
     */
    public static function values($file)
    {
        return array_values(static::decodeAsArray($file));
    }


    /**
     * Combines multiple JSON files into a single JSON file.
     *
     * @param array $files An array of paths to JSON files.
     * @param string|false $result_path The path of the resulting JSON file. If false, a timestamp-based name will be generated.
     * @param int $flags Optional flags to be used during encoding.
     * @return int|false The number of bytes written to the file on success, or false on failure.
     */
    public static function combines(array $files, $result_path = false, $flags = 0)
    {
        if (!$result_path) {
            $result_path = time() . '';
        }
        $finalData = [];
        foreach ($files as $file) {
            array_push($finalData, static::decodeAsArray($file));
        }
        return static::create($result_path, static::encode($finalData), $flags);
    }
}
