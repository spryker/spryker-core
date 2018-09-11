<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Integration;

use Generated\Shared\Transfer\DependencyProviderCollectionTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;

interface DependencyProviderUsedPluginFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\DependencyProviderCollectionTransfer
     */
    public function getUsedPlugins(?ModuleFilterTransfer $moduleFilterTransfer = null): DependencyProviderCollectionTransfer;
}
