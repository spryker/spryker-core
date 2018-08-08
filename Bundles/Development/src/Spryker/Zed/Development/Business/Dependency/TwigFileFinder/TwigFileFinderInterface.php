<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\TwigFileFinder;

use Symfony\Component\Finder\Finder;

interface TwigFileFinderInterface
{
    /**
     * @param string $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function findTwigFiles(string $module): Finder;

    /**
     * @param string $module
     *
     * @return bool
     */
    public function hasModuleTwigFiles(string $module): bool;
}
