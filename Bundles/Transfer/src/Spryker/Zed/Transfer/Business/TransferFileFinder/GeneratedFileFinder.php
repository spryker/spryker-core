<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\TransferFileFinder;

use Symfony\Component\Finder\Finder;

class GeneratedFileFinder implements GeneratedFileFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var string|null
     */
    protected $fileNamePattern;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param string|null $fileNamePattern
     */
    public function __construct(Finder $finder, ?string $fileNamePattern = null)
    {
        $this->finder = $finder;
        $this->fileNamePattern = $fileNamePattern;
    }

    /**
     * @param string $directoryPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directoryPath): Finder
    {
        $finder = clone $this->finder;
        $finder
            ->in($directoryPath)
            ->depth(0)
            ->name($this->getFileNamePattern());

        return $finder;
    }

    /**
     * @return string
     */
    protected function getFileNamePattern(): string
    {
        return $this->fileNamePattern ?? '*';
    }
}
