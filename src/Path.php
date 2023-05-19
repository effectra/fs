<?php

declare(strict_types=1);

namespace Effectra\Fs;

/**
 * Utility class for manipulating file paths.
 */
class Path
{
    /**
     * Retrieves the directory separator for the current platform.
     *
     * @return string The directory separator.
     */
    public static function ds(): string
    {
        return DIRECTORY_SEPARATOR;
    }

    /**
     * Formats a given path by replacing forward slashes, backslashes, and multiple separators with the directory separator.
     *
     * @param string $path The path to format.
     * @return string The formatted path.
     */
    public static function format(string $path): string
    {
        return str_replace(['/', '\\', '.', '//', '\\\\'], static::ds(), $path);
    }

    /**
     * Removes the extension from a given path.
     *
     * @param string $path The path from which to remove the extension.
     * @return string The path without the extension.
     */
    public static function removeExtension(string $path): string
    {
        $ext = '.' . File::extension($path);
        return str_replace($ext, '', $path);
    }

    /**
     * Sets the extension of a given path.
     *
     * @param string $path The path to set the extension for.
     * @param string $ext The extension to set.
     * @return string The path with the specified extension.
     */
    public static function setExtension(string $path, string $ext): string
    {
        return $path . '.' . $ext;
    }
}
