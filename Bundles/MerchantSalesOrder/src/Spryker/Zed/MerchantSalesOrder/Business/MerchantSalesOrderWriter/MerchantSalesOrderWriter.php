<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderWriter;

use Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface;

class MerchantSalesOrderWriter implements MerchantSalesOrderWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface
     */
    protected $merchantSalesOrderRepository;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
     */
    public function __construct(
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager,
        MerchantSalesOrderRepositoryInterface $merchantSalesOrderRepository
    ) {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
        $this->merchantSalesOrderRepository = $merchantSalesOrderRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSalesOrderTransfer $merchantSalesOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderTransfer
     */
    public function createMerchantSalesOrder(MerchantSalesOrderTransfer $merchantSalesOrderTransfer): MerchantSalesOrderTransfer
    {
        $merchantSalesOrderTransfer->requireFkSalesOrder();
        $merchantSalesOrderTransfer->requireOrderReference();
        $merchantSalesOrderTransfer->requireMerchantReference();

        $merchantSalesOrderReference = $this->generateMerchantSalesOrderReference(
            $merchantSalesOrderTransfer->getOrderReference(),
            $merchantSalesOrderTransfer->getMerchantReference()
        );

        $merchantSalesOrderCriteriaFilterTransfer = $this->createMerchantSalesOrderCriteriaFilterTransfer($merchantSalesOrderReference);

        if ($this->merchantSalesOrderRepository->findOne($merchantSalesOrderCriteriaFilterTransfer)) {
            return $merchantSalesOrderTransfer;
        }

        $merchantSalesOrderTransfer->setMerchantSalesOrderReference($merchantSalesOrderReference);

        return $this->merchantSalesOrderEntityManager->createMerchantSalesOrder($merchantSalesOrderTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function generateMerchantSalesOrderReference(string $orderReference, string $merchantReference): string
    {
        return sprintf('%s--%s', $orderReference, $merchantReference);
    }

    /**
     * @param string $merchantSalesOrderReference
     *
     * @return \Generated\Shared\Transfer\MerchantSalesOrderCriteriaFilterTransfer
     */
    protected function createMerchantSalesOrderCriteriaFilterTransfer(string $merchantSalesOrderReference): MerchantSalesOrderCriteriaFilterTransfer
    {
        return (new MerchantSalesOrderCriteriaFilterTransfer())
            ->setMerchantSalesOrderReference($merchantSalesOrderReference);
    }
}
