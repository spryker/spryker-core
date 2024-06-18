<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreContextStorage;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\StoreContextStorage\StoreContextStorageFactory getFactory()
 */
class StoreContextStorageClient extends AbstractClient implements StoreContextStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expandStoreWithTimezone(StoreTransfer $storeTransfer): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreContextExpander()
            ->expandStore($storeTransfer);
    }
}
