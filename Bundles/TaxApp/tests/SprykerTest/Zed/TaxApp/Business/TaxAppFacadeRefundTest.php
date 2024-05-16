<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\TaxApp\TaxAppClient;
use SprykerTest\Zed\TaxApp\TaxAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxApp
 * @group Business
 * @group Facade
 * @group TaxAppFacadeRefundTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeRefundTest extends Unit
{
    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\TaxApp\TaxAppBusinessTester
     */
    protected TaxAppBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * This test will fail if TaxApp is configured but disabled locally due to the way it is constructed.
     * Store logic cannot be stubbed due to the way order saving happens (`getCurrentStore` method is used).
     *
     * @return void
     */
    public function testTaxAppClientWasCalledWhenRefundWasRequestedForAnOrder(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);

        $orderTransfer = $this->getOrderTransferForRefund($storeTransfer);

        $orderItemsIds = array_map(function ($item) {
            return $item->getIdSalesOrderItem();
        }, $orderTransfer->getItems()->getArrayCopy());

        $clientMock = $this->createMock(TaxAppClient::class);

        // Assert
        $clientMock->expects($this->once())->method('requestTaxRefund')->willReturn($this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]));
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);
        $this->tester->mockOauthClient();

        // Act
        $this->tester->getFacade()->processOrderRefund($orderItemsIds, $orderTransfer->getIdSalesOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransferForRefund(StoreTransfer $storeTransfer): OrderTransfer
    {
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(
            static::DEFAULT_OMS_PROCESS_NAME,
            $storeTransfer,
        );
        $orderTransfer->setCreatedAt(date('Y-m-d h:i:s'));
        $orderTransfer->setEmail($orderTransfer->getCustomer()->getEmail());

        foreach ($orderTransfer->getItems() as $item) {
            $item->setSku('some_sku');
            $item->setCanceledAmount($item->getSumPrice());
        }

        return $orderTransfer;
    }
}
