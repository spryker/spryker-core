<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\StoreContextStorage\Business\StoreContextStorageBusinessFactory getFactory()
 */
class StoreContextStorageFacade extends AbstractFacade implements StoreContextStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeStoreContextStorageCollectionByStoreEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createStoreContextStorageWriter()
            ->writeStoreContextStorageCollectionByStoreEvents($eventEntityTransfers);
    }
}
