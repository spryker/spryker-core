<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantWriter;

use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface;

class SalesOrderMerchantWriter implements SalesOrderMerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface
     */
    protected $salesMerchantConnectorEntityManager;

    /**
     * @param \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager
     */
    public function __construct(
        SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager
    ) {
        $this->salesMerchantConnectorEntityManager = $salesMerchantConnectorEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function createSalesOrderMerchant(SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer): ?SalesOrderMerchantTransfer
    {
        $salesOrderMerchantSaveTransfer->requireIdSalesOrder();
        $salesOrderMerchantSaveTransfer->requireOrderReference();
        $salesOrderMerchantSaveTransfer->requireMerchantReference();

        $salesOrderMerchantTransfer = $this->createSalesOrderMerchantTransfer($salesOrderMerchantSaveTransfer);

        return $this->salesMerchantConnectorEntityManager->createSalesOrderMerchant($salesOrderMerchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    protected function createSalesOrderMerchantTransfer(SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer): SalesOrderMerchantTransfer
    {
        $merchantReference = $salesOrderMerchantSaveTransfer->getMerchantReference();
        $salesOrderMerchantReference = $this->generateSalesOrderMerchantReference(
            $salesOrderMerchantSaveTransfer->getOrderReference(),
            $merchantReference
        );

        $salesOrderMerchantTransfer = new SalesOrderMerchantTransfer();
        $salesOrderMerchantTransfer->setMerchantReference($merchantReference);
        $salesOrderMerchantTransfer->setFkSalesOrder($salesOrderMerchantSaveTransfer->getIdSalesOrder());
        $salesOrderMerchantTransfer->setSalesOrderMerchantReference($salesOrderMerchantReference);

        return $salesOrderMerchantTransfer;
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
}
