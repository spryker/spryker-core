<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @deprecated Is replaced by \Spryker\Zed\Transfer\Business\Model\TransferGeneratedDirectory
 */
class TransferCleaner implements TransferCleanerInterface
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return void
     */
    public function cleanDirectory(): void
    {
        if (is_dir($this->directory)) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->findFiles());
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles(): Finder
    {
        $finder = new Finder();
        $finder
            ->in($this->directory)
            ->files()
            ->name('*Transfer.php');

        return $finder;
    }
}
