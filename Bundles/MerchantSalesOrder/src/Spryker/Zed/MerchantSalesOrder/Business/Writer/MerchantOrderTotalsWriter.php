<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface;

class MerchantOrderTotalsWriter implements MerchantOrderTotalsWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface
     */
    protected MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     */
    public function __construct(
        MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository,
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
    ) {
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function updateMerchantOrderTotals(OrderTransfer $orderTransfer): OrderTransfer
    {
        if (!$orderTransfer->getMerchantReferences()) {
            return $orderTransfer;
        }

        $orderTransfer->requireIdSalesOrder()->requireTotals();
        $merchantOrderCollectionTransfer = $this->getMerchantOrderCollection($orderTransfer);

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            $this->merchantSalesOrderEntityManager->updateMerchantOrderTotals(
                $merchantOrderTransfer->getIdMerchantOrderOrFail(),
                $orderTransfer->getTotalsOrFail(),
            );
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    protected function getMerchantOrderCollection(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setMerchantReferences($orderTransfer->getMerchantReferences())
            ->setIdOrder($orderTransfer->getIdSalesOrderOrFail());

        return $this->merchantSalesOrderRepository->getMerchantOrderCollection($merchantOrderCriteriaTransfer);
    }
}
