<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSearch\Business\MerchantSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface getEntityManager()
 */
class MerchantSearchFacade extends AbstractFacade implements MerchantSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer
    {
        return $this->getFactory()
            ->getMerchantFacade()
            ->get($merchantCriteriaTransfer);
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
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantSearchWriter()
            ->writeCollectionByMerchantEvents($eventTransfers);
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
    public function deleteCollectionByMerchantEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createMerchantSearchDeleter()
            ->deleteCollectionByMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getMerchantSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $merchantIds): array
    {
        return $this->getRepository()
            ->getMerchantSynchronizationDataTransfersByIds($filterTransfer, $merchantIds);
    }
}
