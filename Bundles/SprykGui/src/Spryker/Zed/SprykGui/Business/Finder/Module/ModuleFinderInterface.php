<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Module;

use Generated\Shared\Transfer\ModuleFilterTransfer;

interface ModuleFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function findModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array;
}
