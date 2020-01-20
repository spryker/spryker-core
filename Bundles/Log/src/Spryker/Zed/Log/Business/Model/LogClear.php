<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business\Model;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class LogClear implements LogClearInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var array
     */
    protected $logFileDirectories = [];

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param array $logFileDirectories
     */
    public function __construct(Filesystem $filesystem, array $logFileDirectories)
    {
        $this->filesystem = $filesystem;
        $this->logFileDirectories = $logFileDirectories;
    }

    /**
     * @return void
     */
    public function clearLogs()
    {
        foreach ($this->logFileDirectories as $logFileDirectory) {
            $this->removeLogFiles($logFileDirectory);
            $this->removeLogDirectory($logFileDirectory);
        }
    }

    /**
     * @param string $logFileDirectory
     *
     * @return void
     */
    protected function removeLogFiles(string $logFileDirectory): void
    {
        $this->filesystem->remove(
            $this->getLogFilePathsFromDirectory($logFileDirectory)
        );
    }

    /**
     * @param string $logFileDirectory
     *
     * @return void
     */
    protected function removeLogDirectory(string $logFileDirectory)
    {
        if (!$this->filesystem->exists($logFileDirectory)) {
            return;
        }

        try {
            $this->filesystem->remove($logFileDirectory);
        } catch (IOException $e) {
        }
    }

    /**
     * @param string $logFileDirectory
     *
     * @return string[]
     */
    protected function getLogFilePathsFromDirectory(string $logFileDirectory): array
    {
        if (!is_dir($logFileDirectory)) {
            return [];
        }

        $excludeDirPathValues = [
            '.',
            '..',
        ];

        return array_diff(
            scandir($logFileDirectory),
            $excludeDirPathValues
        );
    }
}
