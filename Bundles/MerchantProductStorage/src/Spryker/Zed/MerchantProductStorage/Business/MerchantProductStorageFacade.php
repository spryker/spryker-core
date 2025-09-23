<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductStorage\Business\MerchantProductStorageBusinessFactory getFactory()
 */
class MerchantProductStorageFacade extends AbstractFacade implements MerchantProductStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProductAbstractEvents(array $eventTransfers): void
    {
        $this->getFactory()->createMerchantProductStorageWriter()->writeCollectionByIdMerchantProductAbstractEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement.
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()->createMerchantProductStorageWriter()->writeCollectionByIdMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expandProductConcreteStorages(array $productConcreteStorageTransfers): array
    {
        return $this->getFactory()
            ->createMerchantReferenceProductConcreteStorageExpander()
            ->expandProductConcreteStorages($productConcreteStorageTransfers);
    }
}
