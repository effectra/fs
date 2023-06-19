<?php

declare(strict_types=1);

namespace Effectra\Fs;

use ErrorException;

class File
{
    /**
     * Append content to a file.
     *
     * @param string $path The path to the file.
     * @param mixed  $data The data to append.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    public static function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Check if a file exists.
     *
     * @param string $path The path to the file.
     *
     * @return bool True if the file exists, false otherwise.
     */
    public static function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Delete files.
     *
     * @param string|string[] ...$paths The paths to the files.
     *
     * @return bool True if all files were successfully deleted, false otherwise.
     */
    public static function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (@unlink($path)) {
                    clearstatcache(false, $path);
                } else {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Write content to a file.
     *
     * @param string     $path     The path to the file.
     * @param string     $contents The content to write.
     * @param bool       $lock     Acquire an exclusive write lock on the file.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Replace a string within a file.
     *
     * @param string $search  The string to search for.
     * @param string $replace The replacement string.
     * @param string $path    The path to the file.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    public static function replaceInFile($search, $replace, $path)
    {
        return file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Clear the contents of a file.
     *
     * @param string $path The path to the file.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    public static function clear($path)
    {
        return file_put_contents($path, "");
    }

    /**
     * Get the contents of a file.
     *
     * @param string   $path   The path to the file.
     * @param int|null $length Maximum number of bytes to read. If null, reads the entire file.
     *
     * @return string|false The file contents or false on failure.
     */
    public static function getContent($path, ?int $length = null)
    {
        return file_get_contents(filename: $path, length: $length);
    }

    /**
     * Search for a string within a file.
     *
     * @param string $search The string to search for.
     * @param string $path   The path to the file.
     *
     * @return int|false The position of the search string or false if not found.
     */
    public static function search($search, $path)
    {
        $content = file_get_contents($path);

        return strpos($content, $search);
    }

    /**
     * Get the last modified time of a file.
     *
     * @param string $path The path to the file.
     *
     * @return int|false The last modified time as a Unix timestamp or false on failure.
     */
    public static function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * Check if a file is executable.
     *
     * @param string $path The path to the file.
     *
     * @return bool True if the file is executable, false otherwise.
     */
    public static function isExecutable($path)
    {
        return is_executable($path);
    }

    /**
     * Check if a file is an uploaded file.
     *
     * @param string $path The path to the file.
     *
     * @return bool True if the file is an uploaded file, false otherwise.
     */
    public static function isUploadedFile($path)
    {
        return is_uploaded_file($path);
    }

    /**
     * Check if a file is readable.
     *
     * @param string $path The path to the file.
     *
     * @return bool True if the file is readable, false otherwise.
     */
    public static function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * Check if a file is writable.
     *
     * @param string $path The path to the file.
     *
     * @return bool True if the file is writable, false otherwise.
     */
    public static function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Check if a path points to a regular file.
     *
     * @param string $file The path to the file.
     *
     * @return bool True if the path is a regular file, false otherwise.
     */
    public static function isFile($file)
    {
        return is_file($file);
    }

    /**
     * Find pathnames matching a pattern.
     *
     * @param string $pattern The pattern to search for.
     * @param int    $flags   Flags to control the behavior of the glob function.
     *
     * @return array|false An array containing the matched pathnames or false on failure.
     */
    public static function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }

    /**
     * Get the extension of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string The file extension.
     */
    public static function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get the file type.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The file type or false on failure.
     */
    public static function type($path)
    {
        return filetype($path);
    }

    /**
     * Get the MIME type of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The MIME type or false on failure.
     */
    public static function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    /**
     * Get the base name of a file path.
     *
     * @param string $path The path to the file.
     *
     * @return string The base name of the file.
     */
    public static function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Get the file name without extension.
     *
     * @param string $path The path to the file.
     *
     * @return string The file name without extension.
     */
    public static function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Move a file to a new location.
     *
     * @param string $path   The path to the file.
     * @param string $target The new target path.
     *
     * @return bool True on success, false on failure.
     */
    public static function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * Copy a file to a new location.
     *
     * @param string $path   The path to the file.
     * @param string $target The new target path.
     *
     * @return bool True on success, false on failure.
     */
    public static function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * Get the file size.
     *
     * @param string $path The path to the file.
     *
     * @return int|false The file size in bytes or false on failure.
     */
    public static function size($path)
    {
        return filesize($path);
    }

    /**
     * Include and evaluate a file.
     *
     * @param string $path The path to the file.
     *
     * @return mixed The return value of the included file or false on failure.
     */
    public static function require($path)
    {
        if (static::isFile($path)) {
            return require $path;
        }

        return false;
    }

    /**
     * Include and evaluate a file once.
     *
     * @param string $path The path to the file.
     *
     * @return mixed The return value of the included file or false on failure.
     */
    public static function requireOnce($path)
    {
        if (static::isFile($path)) {
            return require_once $path;
        }

        return false;
    }

    /**
     * Read the lines of a file.
     *
     * @param string $path The path to the file.
     *
     * @return array|false An array containing the lines of the file or false on failure.
     */
    public static function lines($path)
    {
        return file($path, FILE_IGNORE_NEW_LINES);
    }

    /**
     * Get the hash value of a file.
     *
     * @param string $path      The path to the file.
     * @param string $algorithm The hashing algorithm to use.
     *
     * @return string|false The hash value or false on failure.
     */
    public static function hash($path, $algorithm = 'md5')
    {
        return hash_file($algorithm, $path);
    }

    /**
     * Replace the contents of a file.
     *
     * @param string      $path    The path to the file.
     * @param string      $content The new content.
     * @param int|null    $mode    The file mode permissions.
     *
     * @return void
     */
    public static function replace($path, $content, $mode = null)
    {
        file_put_contents($path, $content);

        if ($mode !== null) {
            chmod($path, $mode);
        }
    }
}
