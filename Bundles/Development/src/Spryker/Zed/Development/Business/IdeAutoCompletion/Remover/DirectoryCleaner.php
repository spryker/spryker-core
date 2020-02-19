<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Remover;

use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryCleaner implements DirectoryCleanerInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\GeneratedFileFinderInterface $fileFinder
     */
    protected $fileFinder;

    /**
     * @param array $options
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\GeneratedFileFinderInterface $fileFinder
     */
    public function __construct(array $options, Filesystem $fileSystem, GeneratedFileFinderInterface $fileFinder)
    {
        $this->options = $options;
        $this->fileSystem = $fileSystem;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $baseDirectory = rtrim(
            $this->options[IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY],
            DIRECTORY_SEPARATOR
        );

        $applicationPathFragment = trim(
            str_replace(
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
                $this->options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
                $this->options[IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN]
            ),
            DIRECTORY_SEPARATOR
        );

        $targetDirectory = "{$baseDirectory}/{$applicationPathFragment}/";

        if (!$this->fileSystem->exists($targetDirectory)) {
            return;
        }

        $this->fileSystem->remove(
            $this->fileFinder->findFiles($targetDirectory)
        );

        $this->removeDirectoryRecursiveToBase($targetDirectory, $baseDirectory);
    }

    /**
     * @param string $targetDirectory
     * @param string $basePath
     *
     * @return void
     */
    protected function removeDirectoryRecursiveToBase(string $targetDirectory, string $basePath): void
    {
        $this->removeDirectoryIfEmpty($targetDirectory);

        if (realpath($basePath) === $targetDirectory) {
            return;
        }

        $parentDirectory = realpath(dirname($targetDirectory));
        $this->removeDirectoryRecursiveToBase($parentDirectory, $basePath);
    }

    /**
     * @param string $targetDirectory
     *
     * @return void
     */
    protected function removeDirectoryIfEmpty(string $targetDirectory): void
    {
        if ($this->fileFinder->isEmpty($targetDirectory)) {
            $this->fileSystem->remove($targetDirectory);
        }
    }
}
