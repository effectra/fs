<?php

declare(strict_types=1);

namespace Effectra\Fs\Type;

/**
 * Class Link
 *
 * Provides utility methods for working with symbolic links.
 */
class Link
{
    /**
     * Checks if a path is a symbolic link.
     *
     * @param string $path The path to check.
     * @return bool True if the path is a symbolic link, false otherwise.
     */
    public function isLink($path): bool
    {
        return is_link($path);
    }

    /**
     * Reads the target of a symbolic link.
     *
     * @param string $path The path of the symbolic link.
     * @return string|false The target of the symbolic link on success, or false on failure.
     */
    public function read($path): string|false
    {
        return readlink($path);
    }

    /**
     * Creates a symbolic link.
     *
     * @param string $target The target of the symbolic link.
     * @param string $link The path of the symbolic link to be created.
     * @return bool True on success, false on failure.
     */
    public function symlink(string $target, string $link): bool
    {
        return symlink($target, $link);
    }

    /**
     * Retrieves information about a symbolic link.
     *
     * @param string $path The path of the symbolic link.
     * @return int|false The information about the symbolic link on success, or false on failure.
     */
    public function info($path): int|false
    {
        return linkinfo($path);
    }

    /**
     * Creates a hard link.
     *
     * @param string $target The target of the hard link.
     * @param string $link The path of the hard link to be created.
     * @return bool True on success, false on failure.
     */
    public function create(string $target, string $link): bool
    {
        return link($target, $link);
    }
}
