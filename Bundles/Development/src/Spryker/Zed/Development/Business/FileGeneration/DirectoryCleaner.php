<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\FileGeneration;

use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryCleaner implements DirectoryCleanerInterface
{
    /**
     * @var string
     */
    protected $directoryPath;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Zed\Development\Business\FileGeneration\GeneratedFileFinderInterface $fileFinder
     */
    protected $fileFinder;

    /**
     * @param array $options
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Spryker\Zed\Development\Business\FileGeneration\GeneratedFileFinderInterface $fileFinder
     */
    public function __construct(array $options, Filesystem $fileSystem, GeneratedFileFinderInterface $fileFinder)
    {
        $this->fileSystem = $fileSystem;
        $this->fileFinder = $fileFinder;

        $this->setupDirectories($options);
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        if (!$this->fileSystem->exists($this->directoryPath)) {
            return;
        }

        $this->fileSystem->remove(
            $this->fileFinder->findFiles($this->directoryPath)
        );

        $this->removeDirectory($this->directoryPath);
    }

    /**
     * @param array $options
     *
     * @return void
     */
    private function setupDirectories(array $options): void
    {
        $baseDirectory = rtrim(
            $options[IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY],
            DIRECTORY_SEPARATOR
        );

        $applicationPathFragment = trim(
            str_replace(
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
                $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
                $options[IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN]
            ),
            DIRECTORY_SEPARATOR
        );

        $this->basePath = $baseDirectory;
        $this->directoryPath = "{$baseDirectory}/{$applicationPathFragment}/";
    }

    /**
     * @param string $directoryPath
     *
     * @return void
     */
    private function removeDirectory(string $directoryPath): void
    {
        if ($this->fileFinder->isEmpty($directoryPath)) {
            $this->fileSystem->remove($directoryPath);

            $parent = realpath(dirname($directoryPath));
            if (realpath($this->basePath) === $parent) {
                if ($this->fileFinder->isEmpty($parent)) {
                    $this->fileSystem->remove($parent);
                }
            } else {
                $this->removeDirectory($parent);
            }
        }
    }
}
