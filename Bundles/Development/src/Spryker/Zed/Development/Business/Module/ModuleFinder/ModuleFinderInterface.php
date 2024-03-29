<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFinder;

use Generated\Shared\Transfer\ModuleFilterTransfer;

/**
 * @deprecated Use `spryker/module-finder` instead.
 */
interface ModuleFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ModuleTransfer>
     */
    public function getModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array;
}
