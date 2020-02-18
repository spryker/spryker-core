<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Writer;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderWriter implements MerchantOrderWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     */
    public function __construct(MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager)
    {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrder(OrderTransfer $orderTransfer, string $merchantReference): MerchantOrderTransfer
    {
        $merchantOrderReference = $this->generateMerchantOrderReference(
            $orderTransfer->getOrderReference(),
            $merchantReference
        );

        $merchantOrderTransfer = new MerchantOrderTransfer();
        $merchantOrderTransfer->setMerchantReference($merchantReference);
        $merchantOrderTransfer->setIdOrder($orderTransfer->getIdSalesOrder());
        $merchantOrderTransfer->setMerchantOrderReference($merchantOrderReference);

        return $this->merchantSalesOrderEntityManager->createMerchantOrder($merchantOrderTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function generateMerchantOrderReference(string $orderReference, string $merchantReference): string
    {
        return sprintf('%s--%s', $orderReference, $merchantReference);
    }
}
