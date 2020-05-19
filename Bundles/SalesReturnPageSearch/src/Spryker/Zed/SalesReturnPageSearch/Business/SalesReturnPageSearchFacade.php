<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface getEntityManager()
 */
class SalesReturnPageSearchFacade extends AbstractFacade implements SalesReturnPageSearchFacadeInterface
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
    public function writeCollectionByReturnReasonEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createReturnReasonSearchWriter()
            ->writeCollectionByReturnReasonEvents($eventTransfers);
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
    public function deleteCollectionByReturnReasonEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createReturnReasonSearchDeleter()
            ->deleteCollectionByReturnReasonEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getReturnReasonSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $returnReasonIds = []): array
    {
        return $this->getRepository()->getReturnReasonSynchronizationDataTransfersByIds($filterTransfer, $returnReasonIds);
    }
}
