<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreContextStorage\Plugin\Store;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

/**
 * @method \Spryker\Client\StoreContextStorage\StoreContextStorageFactory getFactory()
 * @method \Spryker\Client\StoreContextStorage\StoreContextStorageClientInterface getClient()
 */
class TimezoneStoreStorageStoreExpanderPlugin extends AbstractPlugin implements StoreExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `StoreTransfer` with timezone.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expand(StoreTransfer $storeTransfer): StoreTransfer
    {
        return $this->getClient()->expandStoreWithTimezone($storeTransfer);
    }
}
