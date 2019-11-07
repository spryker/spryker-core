<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantWriter;

use Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface;
use Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorRepositoryInterface;

class SalesOrderMerchantWriter implements SalesOrderMerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface
     */
    protected $salesMerchantConnectorEntityManager;

    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorRepositoryInterface
     */
    protected $salesMerchantConnectorRepository;

    /**
     * @param \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager
     * @param \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorRepositoryInterface $salesMerchantConnectorRepository
     */
    public function __construct(
        SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager,
        SalesMerchantConnectorRepositoryInterface $salesMerchantConnectorRepository
    ) {
        $this->salesMerchantConnectorEntityManager = $salesMerchantConnectorEntityManager;
        $this->salesMerchantConnectorRepository = $salesMerchantConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $salesOrderMerchantTransfer): ?SalesOrderMerchantTransfer
    {
        $salesOrderMerchantTransfer->requireFkSalesOrder();
        $salesOrderMerchantTransfer->requireOrderReference();
        $salesOrderMerchantTransfer->requireMerchantReference();

        $salesOrderMerchantReference = $this->generateSalesOrderMerchantReference(
            $salesOrderMerchantTransfer->getOrderReference(),
            $salesOrderMerchantTransfer->getMerchantReference()
        );

        $salesOrderMerchantCriteriaFilterTransfer = $this->createSalesOrderMerchantCriteriaFilterTransfer($salesOrderMerchantReference);
        if ($this->salesMerchantConnectorRepository->findOne($salesOrderMerchantCriteriaFilterTransfer)) {
            return $salesOrderMerchantTransfer;
        }

        $salesOrderMerchantTransfer->setSalesOrderMerchantReference($salesOrderMerchantReference);

        return $this->salesMerchantConnectorEntityManager->createSalesOrderMerchant($salesOrderMerchantTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function generateSalesOrderMerchantReference(string $orderReference, string $merchantReference): string
    {
        return sprintf('%s--%s', $orderReference, $merchantReference);
    }

    /**
     * @param string $salesOrderMerchantReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer
     */
    protected function createSalesOrderMerchantCriteriaFilterTransfer(string $salesOrderMerchantReference): SalesOrderMerchantCriteriaFilterTransfer
    {
        return (new SalesOrderMerchantCriteriaFilterTransfer())
            ->setSalesOrderMerchantReference($salesOrderMerchantReference);
    }
}
