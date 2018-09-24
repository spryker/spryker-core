<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ProjectModuleFinder;

use Generated\Shared\Transfer\ModuleFilterTransfer;

interface ProjectModuleFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return array
     */
    public function getProjectModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array;
}
