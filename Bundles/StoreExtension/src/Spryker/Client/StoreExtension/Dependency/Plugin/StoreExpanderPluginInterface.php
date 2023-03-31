<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;

interface StoreExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands store on store getting.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expand(StoreTransfer $storeTransfer): StoreTransfer;
}
