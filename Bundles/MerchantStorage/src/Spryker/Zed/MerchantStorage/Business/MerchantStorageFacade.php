<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface getEntityManager()
 */
class MerchantStorageFacade extends AbstractFacade implements MerchantStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeByMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantStorageWriter()
            ->writeCollectionByMerchantEvents($eventTransfers);
    }
}
