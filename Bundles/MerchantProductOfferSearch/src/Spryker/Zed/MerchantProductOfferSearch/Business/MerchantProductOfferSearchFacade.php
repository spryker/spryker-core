<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Business\MerchantProductOfferSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 */
class MerchantProductOfferSearchFacade extends AbstractFacade implements MerchantProductOfferSearchFacadeInterface
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
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeCollectionByIdMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductOfferEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantProductOfferSearchWriter()
            ->writeCollectionByIdProductOfferEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferSearchReader()
            ->getProductAbstractMerchantDataByProductAbstractIds($productAbstractIds);
    }
}
