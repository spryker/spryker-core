<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\FileGeneration;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PatternFileFinder extends AbstractDirectoryFileFinder
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param string $pattern
     */
    public function __construct(Finder $finder, string $pattern)
    {
        parent::__construct($finder);

        $this->pattern = $pattern;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileEntry
     *
     * @return bool
     */
    protected function filterFile(SplFileInfo $fileEntry): bool
    {
        return preg_match($this->pattern, $fileEntry->getRealPath()) === 1;
    }
}
