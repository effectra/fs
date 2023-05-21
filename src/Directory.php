<?php

declare(strict_types=1);

namespace Effectra\Fs;

use FilesystemIterator;

/**
 * Utility class for working with directories.
 */
class Directory
{
    /**
     * Checks if a given path is a directory.
     *
     * @param string $directory The directory path to check.
     * @return bool True if the path is a directory, false otherwise.
     */
    public static function isDirectory(string $directory): bool
    {
        return is_dir($directory);
    }

    /**
     * Creates a new directory.
     *
     * @param string $path The path of the directory to create.
     * @param int $mode The permissions for the new directory (default: 0755).
     * @param bool $recursive Whether to create parent directories if they don't exist (default: false).
     * @param bool $force Whether to suppress errors and create the directory forcefully (default: false).
     * @return bool True on success, false on failure.
     */
    public static function make(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Deletes a directory.
     *
     * @param string $directory The directory to delete.
     * @param bool $preserve Whether to preserve the directory if it's not empty (default: false).
     * @return bool True on success, false on failure.
     */
    public static function delete(string $directory, bool $preserve = false): bool
    {
        if (!static::isDirectory($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                static::delete($item->getPathname());
            } else {
                File::delete($item->getPathname());
            }
        }

        if (!$preserve) {
            @rmdir($directory);
        }

        return true;
    }

    /**
     * Deletes all directories within a directory.
     *
     * @param string $directory The directory from which to delete the directories.
     * @return bool True on success, false on failure.
     */
    public static function deleteDirectories(string $directory): bool
    {
        if (!static::isDirectory($directory)) {
            return false;
        }

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                static::delete($item->getPathname());
            }
        }

        return true;
    }

    /**
     * Gets a directory instance.
     *
     * @param string $directory The directory path.
     * @return resource|false A directory resource on success, false on failure.
     * @throws \Exception If the path is not a directory.
     */
    public static function instance(string $directory)
    {
        if (!static::isDirectory($directory)) {
            throw new \Exception("This is not a directory");
        }

        return dir($directory);
    }

    /**
     * Copies a directory recursively to a destination.
     *
     * @param string $source The source directory to copy.
     * @param string $destination The destination directory.
     * @return bool True on success, false on failure.
     */

    function copy(string $source, $destination)
    {
        // Check if the source is a directory
        if (is_dir($source)) {
            // Create the destination directory if it doesn't exist
            if (!is_dir($destination)) {
                mkdir($destination);
            }

            // Loop through all the files and subdirectories in the source directory
            $files = scandir($source);
            foreach ($files as $file) {
                // Skip the current and parent directory links
                if ($file == '.' || $file == '..') {
                    continue;
                }

                // Recursive call to copy subdirectories
                if (is_dir($source . '/' . $file)) {
                    static::copy($source . '/' . $file, $destination . '/' . $file);
                }
                // Copy files
                else {
                    copy($source . '/' . $file, $destination . '/' . $file);
                }
            }

            return true;
        }

        return false;
    }
    /**
     * Retrieves the name of a directory from its path.
     *
     * @param string $directory The directory path.
     * @return string The name of the directory.
     */
    public static function name(string $directory)
    {
        return File::name($directory);
    }
    /**
     * Renames a directory.
     *
     * @param string $from The current directory name or path.
     * @param string $to The new directory name or path.
     * @param resource|null $context The context resource (default: null).
     * @return bool True on success, false on failure.
     */
    public static function rename(string $from, string $to, $context = null): bool
    {
        return rename($from, $to, $context);
    }
    /**
     * Retrieves the full path of a directory's parent.
     *
     * @param string $directory The directory path.
     * @return string The full path of the parent directory.
     */
    public static function fullName($directory)
    {
        return dirname($directory);
    }

    public static function read($directory)
    {
        $entries = [];
        if ($handle = opendir($directory)) {

            while (false !== ($entry = readdir($handle))) {
                array_push($entries, $entry);
            }

            while ($entry = readdir($handle)) {
                array_push($entries, $entry);
            }

            closedir($handle);
        }
        return $entries;
    }
    /**
     * Retrieves the parent directory of a given directory path.
     *
     * @param string $directory The directory path.
     * @param int $levels The number of levels to go up in the directory hierarchy (default: 1).
     * @return string The parent directory path.
     */
    public static function parent($directory, $levels = 1)
    {
        return dirname($directory, $levels);
    }
    /**
     * Checks if a directory is considered private.
     *
     * @param string $directory The directory path.
     * @return bool True if the directory is private, false otherwise.
     * @throws \Exception If the path is not a directory.
     */
    public static function isPrivate($directory)
    {
        if (!static::isDirectory($directory)) {
            throw new \Exception("This is not directory");
        }

        $dir_name = strpos(basename($directory), '.');

        if ($dir_name !== false) {
            return true;
        }

        return  $dir_name;
    }
    /**
     * Retrieves the files within a directory.
     *
     * @param string $directory The directory path.
     * @param bool $full_path Whether to include the full path of the files (default: false).
     * @param bool|string|array $only_extension Filter files by extension(s) (default: false).
     * @return array|null An array of files or null if no files found.
     * @throws \Exception If the path is not a directory.
     */
    public static function files($directory, $full_path = false, $only_extension = false)
    {
        if (!static::isDirectory($directory)) {
            throw new \Exception("This is not directory");
        }
        $dir = scandir($directory);

        $files = [];

        $exclude = ['.', '..'];

        if ($only_extension) {
            if (is_string($only_extension)) {
                $exclude = [$only_extension];
            }
            if (is_array($only_extension)) {
                $exclude = $only_extension;
            }
        }

        foreach ($dir as $file) {

            $__full_file = $directory . DIRECTORY_SEPARATOR . $file;

            $__file = $full_path ? $__full_file : $file;

            $ext = File::extension($__full_file);

            if ($only_extension) {
                if (in_array($ext, $exclude)) {
                    array_push($files, $__file);
                }
            } else {
                if (!in_array($file, $exclude)) {
                    array_push($files, $__file);
                }
            }
        }

        if (count($files) == 0) {
            return null;
        }

        return $files;
    }
    /**
     * Checks if a directory has any files.
     *
     * @param string $directory The directory path.
     * @return bool True if the directory has files, false otherwise.
     * @throws \Exception If the path is not a directory.
     */
    public static function hasFiles($directory)
    {
        if (!static::isDirectory($directory)) {
            throw new \Exception("This is not directory");
        }

        $scan = scandir($directory);

        unset($scan[0], $scan[1]);

        if (!empty($scan)) {
            return true;
        }
        return false;
    }
    /**
     * Deletes files within a directory.
     *
     * @param string $directory The directory path.
     * @param bool|string|array $only_extension Filter files by extension(s) (default: false).
     * @return bool True if files were deleted successfully, false otherwise.
     * @throws \Exception If the path is not a directory.
     */
    public static function deleteFiles($directory, $only_extension = false)
    {
        if (!static::hasFiles($directory)) {
            return false;
        }

        $files = static::files($directory, true, $only_extension);

        if (!$files) {
            return false;
        }

        return File::delete($files);
    }
    /**
     * Retrieves the directories within a directory.
     *
     * @param string $directory The directory path.
     * @param bool $full_path Whether to include the full path of the directories (default: false).
     * @return array|null An array of directories or null if no directories found.
     * @throws \Exception If the path is not a directory.
     */
    public static function directories($directory, $full_path = false)
    {
        if (!static::isDirectory($directory)) {
            return false;
        }

        $files = [];

        $items = new FilesystemIterator($directory);

        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                $files[] = $full_path ?  $item->getPathname() : static::name($item->getPathname());
            }
        }

        if (count($files) == 0) {
            return null;
        }

        return $files;
    }
    /**
     * Empties a directory by deleting its files and subdirectories.
     *
     * @param string $directory The directory path.
     * @return void
     */
    public static function empty($directory)
    {
        static::deleteFiles($directory);

        static::deleteDirectories($directory);
    }
}
