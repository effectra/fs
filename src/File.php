<?php

declare(strict_types=1);

namespace Effectra\Fs;

use ErrorException;

class File
{
    public static function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    public static function exists($path)
    {
        return file_exists($path);
    }

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

    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    public static function replaceInFile($search, $replace, $path)
    {
        return file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    public static function clear($path)
    {
        return file_put_contents($path, "");
    }

    public static function getContent($path, ?int $length = null)
    {
        return file_get_contents(filename: $path, length: $length);
    }

    public static function search($search, $path)
    {
        $content =  file_get_contents($path);

        return strpos($content, $search);
    }

    public static function lastModified($path)
    {
        return filemtime($path);
    }

    public static function isExecutable($path)
    {
        return is_executable($path);
    }

    public static function isUploadedFile($path)
    {
        return is_uploaded_file($path);
    }

    public static function isReadable($path)
    {
        return is_readable($path);
    }

    public static function isWritable($path)
    {
        return is_writable($path);
    }

    public static function isFile($file)
    {
        return is_file($file);
    }

    public static function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }

    public static function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function type($path)
    {
        return filetype($path);
    }

    public static function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    public static function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    public static function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function move($path, $target)
    {
        return rename($path, $target);
    }

    public static function copy($path, $target)
    {
        return copy($path, $target);
    }

    public static function size($path)
    {
        return filesize($path);
    }

    public static function require($path)
    {
        if (static::isFile($path)) {
            return require $path;
        }

        return false;
    }

    public static function requireOnce($path)
    {
        if (static::isFile($path)) {
            return require_once $path;
        }

        return false;
    }

    public static function lines($path)
    {
    }

    public static function hash($path, $algorithm = 'md5')
    {
        return hash_file($algorithm, $path);
    }

    public static function replace($path, $content, $mode = null)
    {
        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        if (!is_null($mode)) {
            chmod($tempPath, $mode);
        } else {
            chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        rename($tempPath, $path);
    }

    public static function chmod($path, $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }

    public static function perms($path)
    {
        return fileperms($path);
    }

    public static function fileATime($path)
    {
        return fileatime($path);
    }

    public static function fileCTime($path)
    {
        return filectime($path);
    }

    public static function fileGroup($path)
    {
        return filegroup($path);
    }

    public static function fileOwner($path)
    {
        return fileowner($path);
    }
}
