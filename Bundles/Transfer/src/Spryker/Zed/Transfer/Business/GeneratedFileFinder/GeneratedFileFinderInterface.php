<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\GeneratedFileFinder;

use Symfony\Component\Finder\Finder;

interface GeneratedFileFinderInterface
{
    /**
     * @param string $directoryPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directoryPath): Finder;
}
