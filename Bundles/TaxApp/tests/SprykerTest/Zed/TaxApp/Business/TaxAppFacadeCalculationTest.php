<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\TaxApp\TaxAppClient;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use SprykerTest\Zed\TaxApp\TaxAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxApp
 * @group Business
 * @group Facade
 * @group TaxAppFacadeCalculationTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeCalculationTest extends Unit
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
     * @return void
     */
    public function testCalculableObjectHasTaxTotalWhenRecalculateRequestsTaxFromExternalApiSuccessfully(): void
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $this->tester->mockOauthClient();

        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $this->assertEquals($taxCalculationResponseTransfer->getSale()->getTaxTotal(), $calculableObjectTransfer->getTotals()->getTaxTotal()->getAmount());
        $this->assertGreaterThanOrEqual(0, $calculableObjectTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculableObjectDoesNotHaveTaxTotalWhenShipmentIsMissing(): void
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore()]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransferWithoutShipment($storeTransfer);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->never())->method('requestTaxQuotation');
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $this->tester->mockOauthClient();

        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $this->assertNull($calculableObjectTransfer->getTotals()->getTaxTotal());
    }

    /**
     * @return void
     */
    public function testCalculableObjectIsExpandedWithTaxMetadataWhenRecalculateMethodIsCalled(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore()]);
        $calculableObjectTransfer = $this->tester->haveQuoteTransfer(['items' => [[]]]);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $this->tester->mockOauthClient();

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->tester->assertCalculableObjectTransferExtendedWithTaxMetadata($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasSaleTransferExpandedWithMerchantStockAddressWhenRecalculateMethodIsCalled(): void
    {
        // Arrange
        $taxAppClientMock = $this->makeEmpty(TaxAppClientInterface::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $storeTransfer = $this->tester->haveStore([], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore()]);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        /** @var \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer */
        $calculableObjectTransfer = $this->tester->haveCalculableObjectTransferWithMerchantStockAddress($storeTransfer);

        $this->tester->mockOauthClient();

        // Assert
        $this->tester->assertRequestTaxQuotationReceivesSalesItemMappedWithMerchantStockAddress($taxAppClientMock);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasSaleTransferWithItemsWhenMerchantStockAddressIsEmpty(): void
    {
        // Arrange
        $taxAppClientMock = $this->makeEmpty(TaxAppClientInterface::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $storeTransfer = $this->tester->haveStore([], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore()]);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        /** @var \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer */
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);

        $this->tester->mockOauthClient();

        // Assert
        $this->tester->assertRequestTaxQuotationReceivesSalesItemWithCorrectItemsAndWithoutWarehouseAddress(
            $taxAppClientMock,
            $calculableObjectTransfer,
        );

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }
}
