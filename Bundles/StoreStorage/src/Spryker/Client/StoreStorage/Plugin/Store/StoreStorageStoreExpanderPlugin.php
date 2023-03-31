<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Plugin\Store;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

/**
 * @method \Spryker\Client\StoreStorage\StoreStorageFactory getFactory()
 */
class StoreStorageStoreExpanderPlugin extends AbstractPlugin implements StoreExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expand(StoreTransfer $storeTransfer): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreExpander()
            ->expandStore($storeTransfer);
    }
}
