<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Test\Unit;
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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE', StoreTransfer::NAME => 'DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->once())->method('requestTaxRefund')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $this->tester->mockOauthClient();

        $orderTransfer = $this->tester->getOrderTransferForRefund($storeTransfer);

        $orderItemsIds = array_map(function ($item) {
            return $item->getIdSalesOrderItem();
        }, $orderTransfer->getItems()->getArrayCopy());

        $this->tester->getFacade()->processOrderRefund($orderItemsIds, $orderTransfer->getIdSalesOrder());
    }
}
