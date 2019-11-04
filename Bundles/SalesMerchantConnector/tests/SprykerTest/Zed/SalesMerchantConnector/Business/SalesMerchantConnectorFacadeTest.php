<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesMerchantConnector
 * @group Business
 * @group Facade
 * @group SalesMerchantConnectorFacadeTest
 * Add your own group annotations below this line
 */
class SalesMerchantConnectorFacadeTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesMerchantConnector\SalesMerchantConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);
    }

    /**
     * @return void
     */
    public function testAddOrderReferencesAddsOrderItemReference(): void
    {
        //arrange
        $idSalesOrderItem = 1;
        $itemTransfer = (new ItemBuilder())->build();
        $salesOrderItemEntityTransfer = (new SpySalesOrderItemEntityTransfer())->setIdSalesOrderItem($idSalesOrderItem);

        //act
        $newSalesOrderItemEntityTransfer =
            $this->getFacade()->expandOrderItemWithReferences($salesOrderItemEntityTransfer, $itemTransfer);

        //assert
        $this->assertEquals($this->getSalesOrderItemReference($idSalesOrderItem), $newSalesOrderItemEntityTransfer->getOrderItemReference());
    }

    /**
     * @return void
     */
    public function testAddOrderReferencesDoesNotAddMerchantReferenceWhenNull(): void
    {
        //arrange
        $itemTransfer = (new ItemBuilder(['merchantReference' => null]))->build();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        //act
        $newSalesOrderItemEntityTransfer =
            $this->getFacade()->expandOrderItemWithReferences($salesOrderItemEntityTransfer, $itemTransfer);

        //assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testAddOrderReferencesToSalesOrderItemAddsValueToDatabaseEntity(): void
    {
        //arrange
        $idSalesOrderItem = 1;
        $merchantReference = 'MER-12';
        $itemTransfer = (new ItemBuilder(['merchantReference' => $merchantReference]))->build();
        $salesOrderItemEntityTransfer = (new SpySalesOrderItemEntityTransfer())->setIdSalesOrderItem($idSalesOrderItem);

        //act
        $newSalesOrderItemEntityTransfer =
            $this->getFacade()->expandOrderItemWithReferences($salesOrderItemEntityTransfer, $itemTransfer);

        //assert
        $this->assertEquals($merchantReference, $newSalesOrderItemEntityTransfer->getMerchantReference());
        $this->assertEquals($this->getSalesOrderItemReference($idSalesOrderItem), $newSalesOrderItemEntityTransfer->getOrderItemReference());
    }

    /**
     * @return void
     */
    public function testCreateSalesOrderMerchant(): void
    {
        //Arrange
        $saveOrderTransfer = $this->tester->haveOrder([
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $salesOrderMerchantSaveTransfer = new SalesOrderMerchantSaveTransfer();
        $salesOrderMerchantSaveTransfer->setOfferReference($merchantProductOfferTransfer->getProductOfferReference());
        $salesOrderMerchantSaveTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        //Act
        $salesOrderMerchantTransfer = $this->tester->getLocator()->salesMerchantConnector()->facade()->createSalesOrderMerchant($salesOrderMerchantSaveTransfer);

        //Assert
        $this->assertIsInt($salesOrderMerchantTransfer->getIdSalesOrderMerchant());
        $this->assertEquals($salesOrderMerchantTransfer->getMerchantReference(), $merchantTransfer->getMerchantKey());
        $this->assertEquals($salesOrderMerchantTransfer->getFkSalesOrder(), $saveOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return string
     */
    protected function getSalesOrderItemReference(int $idSalesOrderItem): string
    {
        return md5(sprintf('SOI-%s', (string)$idSalesOrderItem));
    }
}
