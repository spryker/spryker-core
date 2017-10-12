<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DataBuilderCleaner implements TransferCleanerInterface
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
    public function cleanDirectory()
    {
        if (is_dir($this->directory)) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->findFiles());
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->directory)
            ->files()
            ->name('*Builder.php');

        return $finder;
    }
}
