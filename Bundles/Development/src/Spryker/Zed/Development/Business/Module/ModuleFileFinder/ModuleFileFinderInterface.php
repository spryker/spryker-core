<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFileFinder;

use Generated\Shared\Transfer\ModuleTransfer;
use Symfony\Component\Finder\Finder;

interface ModuleFileFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find(ModuleTransfer $moduleTransfer): Finder;
}
