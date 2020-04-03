<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Remover;

use Symfony\Component\Filesystem\Filesystem;

class DirectoryRemover implements DirectoryRemoverInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\TargetDirectoryResolver
     */
    private $targetDirectoryResolver;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\GeneratedFileFinderInterface $fileFinder
     */
    protected $fileFinder;

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\TargetDirectoryResolver $targetDirectoryResolver
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Remover\GeneratedFileFinderInterface $fileFinder
     */
    public function __construct(TargetDirectoryResolver $targetDirectoryResolver, Filesystem $fileSystem, GeneratedFileFinderInterface $fileFinder)
    {
        $this->targetDirectoryResolver = $targetDirectoryResolver;
        $this->fileSystem = $fileSystem;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param string $application
     *
     * @return void
     */
    public function remove(string $application): void
    {
        $targetDirectory = $this->targetDirectoryResolver->resolveTargetDirectory($application);

        if (!$this->fileSystem->exists($targetDirectory)) {
            return;
        }

        $this->fileSystem->remove(
            $this->fileFinder->findFiles($targetDirectory)
        );
    }
}
