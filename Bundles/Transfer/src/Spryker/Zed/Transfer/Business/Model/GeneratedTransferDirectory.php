<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class GeneratedTransferDirectory implements GeneratedTransferDirectoryInterface
{

    /**
     * @var string
     */
    protected $directoryPath;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param string $directoryPath
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     */
    public function __construct($directoryPath, Filesystem $fileSystem, Finder $finder)
    {
        $this->directoryPath = $directoryPath;
        $this->fileSystem = $fileSystem;
        $this->finder = $finder;
    }

    /**
     * @return void
     */
    public function clear()
    {
        if (!$this->fileSystem->exists($this->directoryPath)) {
            return;
        }

        $this->fileSystem->remove($this->findFiles());
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles()
    {
        $finder = clone $this->finder;
        $finder
            ->in($this->directoryPath)
            ->depth(0);

        return $finder;
    }

}
