<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Spryker\Zed\Transfer\Business\TransferFileFinder\GeneratedFileFinderInterface;
use Symfony\Component\Filesystem\Filesystem;

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
     * @var \Spryker\Zed\Transfer\Business\TransferFileFinder\GeneratedFileFinderInterface
     */
    protected $fileFinder;

    /**
     * @param string $directoryPath
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Spryker\Zed\Transfer\Business\TransferFileFinder\GeneratedFileFinderInterface $fileFinder
     */
    public function __construct($directoryPath, Filesystem $fileSystem, GeneratedFileFinderInterface $fileFinder)
    {
        $this->directoryPath = $directoryPath;
        $this->fileSystem = $fileSystem;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @return void
     */
    public function clear()
    {
        if (!$this->fileSystem->exists($this->directoryPath)) {
            return;
        }

        $this->fileSystem->remove(
            $this->fileFinder->findFiles($this->directoryPath)
        );
    }
}
