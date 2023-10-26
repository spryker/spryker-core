<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Client\TaxApp\TaxAppClient;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountAmountAggregatorForGenericAmountPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\GrandTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSubtotalAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
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
    public function testCalculableObjectHasTheSameTaxRequestHashWhenRecalculateWasCalledTwiceWithoutChanges(): void
    {
        // Arrange
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

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $firstCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $secondCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $this->assertEquals($firstCalculationHash, $secondCalculationHash);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasTheDifferentTaxRequestHashWhenWasRecalculateCalledTwiceWithChanges(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->exactly(2))->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $this->tester->mockOauthClient();

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $firstCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $calculableObjectTransfer->getOriginalQuote()->setUuid(Uuid::uuid4()->toString());

        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $secondCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $this->assertNotEquals($firstCalculationHash, $secondCalculationHash);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasZeroTaxTotalWhenShipmentIsMissing(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['fk_store' => $storeTransfer->getIdStore()]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransferWithoutShipment($storeTransfer);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->never())->method('requestTaxQuotation');
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $originalQuote = $calculableObjectTransfer->getOriginalQuote();
        $originalQuote->setPriceMode('NET_MODE');

        $calculationFacade = $this->tester->createCalculationFacade(
            [
                new PriceCalculatorPlugin(),
                new ItemSubtotalAggregatorPlugin(),

                new DiscountAmountAggregatorForGenericAmountPlugin(),
                new ItemDiscountAmountFullAggregatorPlugin(),
            ],
        );
        $calculationFacade->recalculateQuote($originalQuote);
        $calculableObjectTransfer->setItems($originalQuote->getItems());

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertEquals(0, $calculableObjectTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculableObjectIsExpandedWithTaxMetadataWhenRecalculateMethodIsCalled(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore()]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);

        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

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
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $taxAppClientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
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
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $taxAppClientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
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

    /**
     * @return void
     */
    public function testQuoteHasCorrectGrandTotalWhenPriceModeIsNetAndRecalculateRequestsTaxFromExternalApiSuccessfully(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);

        $this->tester->setQuoteTaxMetadataExpanderPlugins();
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        $originalQuote = $calculableObjectTransfer->getOriginalQuote();

        $calculationFacade = $this->tester->createCalculationFacade(
            [
                new GrandTotalCalculatorPlugin(),
            ],
        );
        $calculationFacade->recalculateQuote($originalQuote);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->tester->assertQuoteHasCorrectGrandTotal($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteHasCorrectGrandTotalWhenPriceModeIsGrossAndRecalculateRequestsTaxFromExternalApiSuccessfully(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $storeTransfer->getIdStore(), 'isActive' => true]);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);
        $calculableObjectTransfer->setPriceMode('GROSS_MODE');

        $originalQuote = $calculableObjectTransfer->getOriginalQuote();

        $calculationFacade = $this->tester->createCalculationFacade(
            [
                new GrandTotalCalculatorPlugin(),
            ],
        );
        $calculationFacade->recalculateQuote($originalQuote);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->tester->assertQuoteHasCorrectGrandTotal($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteHasHideTaxInCartFlagWhenTaxAppIsActive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['fk_store' => $storeTransfer->getIdStore(), 'is_active' => true]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        $this->tester->mockOauthClient();

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertTrue($calculableObjectTransfer->getOriginalQuote()->getHideTaxInCart());
    }

    /**
     * @return void
     */
    public function testQuoteDoesNotHaveHideTaxInCartFlagWhenTaxAppIsNotActive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::STORE_REFERENCE => 'dev-DE'], false);
        $this->tester->setStoreReferenceData(['DE' => 'dev-DE']);
        $this->tester->haveTaxAppConfig(['fk_store' => $storeTransfer->getIdStore(), 'is_active' => false]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertFalse($calculableObjectTransfer->getOriginalQuote()->getHideTaxInCart());
    }
}
