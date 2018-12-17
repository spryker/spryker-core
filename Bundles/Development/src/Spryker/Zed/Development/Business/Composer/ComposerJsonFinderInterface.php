<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ModuleTransfer;
use Symfony\Component\Finder\SplFileInfo;

interface ComposerJsonFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    public function findByModule(ModuleTransfer $module): ?SplFileInfo;
}
