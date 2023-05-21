<?php

declare(strict_types=1);

namespace Effectra\Fs;

/**
 * Utility class for retrieving disk space information.
 */
class Disk
{
    /**
     * Retrieves the available disk space in bytes for the specified target.
     *
     * @param string $target The target directory or disk path.
     * @return float|false The available disk space in bytes, or false on failure.
     */
    public function spaceAvailable(string $target): float|false
    {
        return disk_free_space($target);
    }

    /**
     * Retrieves the total disk space in bytes for the specified target.
     *
     * @param string $target The target directory or disk path.
     * @return float|false The total disk space in bytes, or false on failure.
     */
    public function spaceTotal(string $target): float|false
    {
        return disk_total_space($target);
    }
}
