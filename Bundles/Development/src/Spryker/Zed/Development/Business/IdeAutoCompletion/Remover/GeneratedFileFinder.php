<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Remover;

use Symfony\Component\Finder\Finder;

class GeneratedFileFinder implements GeneratedFileFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $directoryPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directoryPath): Finder
    {
        $finder = clone $this->finder;
        $finder->in($directoryPath)
            ->depth(0)
            ->name('/.*\.php/');

        return $finder;
    }

    /**
     * @param string $directoryPath
     *
     * @return bool
     */
    public function isEmpty(string $directoryPath): bool
    {
        $finder = clone $this->finder;

        return $finder->in($directoryPath)
                ->depth(0)
                ->exclude(['.', '..'])
                ->count() === 0;
    }
}
