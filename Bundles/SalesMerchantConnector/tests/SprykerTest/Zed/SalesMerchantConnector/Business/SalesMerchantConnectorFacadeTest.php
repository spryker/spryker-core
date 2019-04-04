<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantConnector\Business;

use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantConnector
 * @group Business
 * @group Facade
 * @group SalesOrderThresholdFacadeTest
 * Add your own group annotations below this line
 */
class SalesMerchantConnectorFacadeTest extends SalesMerchantConnectorMocks
{
    /**
     * @var \SprykerTest\Zed\SalesMerchantConnector\SalesMerchantConnectorBusinessTester
     */
    protected $tester;

    protected const MOCK_MERCHANT_ORDER_REFERENCE_PATTERN = '%s/%s';

    /**
     * @return void
     */
    public function testAddMerchantOrderReferenceChangesNothingWhenNoMerchantIdInItemTransfer(): void
    {
        //arrange
        $itemTransfer = (new ItemBuilder(['fk_merchant' => null]))->build();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        //act
        $newSalesOrderItemEntityTransfer =
            $this->getFacade()->addMerchantOrderReferenceToSalesOrderItem($salesOrderItemEntityTransfer, $itemTransfer);

        //assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getMerchantOrderReference());
    }

    /**
     * @return void
     */
    public function testAddMerchantOrderReferenceToSalesOrderItemIsSuccessful(): void
    {
        //arrange
        $idSalesOrder = 1;
        $merchantId = 2;
        $itemTransfer = (new ItemBuilder(['fk_merchant' => $merchantId]))->build();
        $salesOrderItemEntityTransfer = (new SpySalesOrderItemEntityTransfer())->setFkSalesOrder($idSalesOrder);

        //act
        $newSalesOrderItemEntityTransfer =
            $this->getFacade()->addMerchantOrderReferenceToSalesOrderItem($salesOrderItemEntityTransfer, $itemTransfer);

        //assert
        $this->assertSame(
            sprintf(
                static::MOCK_MERCHANT_ORDER_REFERENCE_PATTERN,
                $salesOrderItemEntityTransfer->getFkSalesOrder(),
                $itemTransfer->getFkMerchant()
            ),
            $newSalesOrderItemEntityTransfer->getMerchantOrderReference()
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorFacadeInterface
     */
    protected function getFacade()
    {
        $config = $this->createSalesMerchantConnectorConfigMock();
        $factory = $this->createSalesMerchantConnectorBusinessFactoryMock($config);
        $factory->getConfig()
            ->expects($this->any())
            ->method('getMerchantOrderReferencePattern')
            ->willReturn(static::MOCK_MERCHANT_ORDER_REFERENCE_PATTERN);
        return $this->createSalesMerchantConnectorFacadeMock($factory);
    }
}
