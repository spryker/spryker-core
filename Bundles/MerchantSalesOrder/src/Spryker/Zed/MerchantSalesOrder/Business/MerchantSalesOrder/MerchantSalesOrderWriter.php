<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantSalesOrderWriter implements MerchantSalesOrderWriterInterface
{
    protected const FORMAT_MERCHANT_SALES_ORDER_REFERENCE = '%s--%s';

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     */
    public function __construct(
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
    ) {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantSalesOrder(
        OrderTransfer $orderTransfer,
        string $merchantReference
    ): MerchantOrderTransfer {
        $orderTransfer->requireIdSalesOrder();
        $orderTransfer->requireOrderReference();

        $merchantSalesOrderReference = $this->generateMerchantSalesOrderReference(
            $orderTransfer->getOrderReference(),
            $merchantReference
        );

        $merchantOrderTransfer = new MerchantOrderTransfer();
        $merchantOrderTransfer->setMerchantReference($merchantReference);
        $merchantOrderTransfer->setIdSalesOrder($orderTransfer->getIdSalesOrder());
        $merchantOrderTransfer->setMerchantSalesOrderReference($merchantSalesOrderReference);

        return $this->merchantSalesOrderEntityManager->createMerchantSalesOrder($merchantOrderTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function generateMerchantSalesOrderReference(string $orderReference, string $merchantReference): string
    {
        return sprintf(static::FORMAT_MERCHANT_SALES_ORDER_REFERENCE, $orderReference, $merchantReference);
    }
}
