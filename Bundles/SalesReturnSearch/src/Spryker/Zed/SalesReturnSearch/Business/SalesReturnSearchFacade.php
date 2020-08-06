<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesReturnSearch\Business\SalesReturnSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface getEntityManager()
 */
class SalesReturnSearchFacade extends AbstractFacade implements SalesReturnSearchFacadeInterface
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
