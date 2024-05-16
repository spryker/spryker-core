<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Ramsey\Uuid\Uuid;
use Spryker\Client\TaxApp\TaxAppClient;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\DiscountAmountAggregatorForGenericAmountPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\GrandTotalCalculatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemDiscountAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemSubtotalAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
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
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\TaxApp\TaxAppBusinessTester
     */
    protected TaxAppBusinessTester $tester;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::COUNTRIES => ['US']], false);
        $this->tester->haveTaxAppConfig(['vendor_code' => 'vendorCode', 'fk_store' => $this->storeTransfer->getIdStore(), 'is_active' => true]);
        $this->tester->setQuoteTaxMetadataExpanderPlugins();
        $this->tester->mockOauthClient();

        $storeFacadeMock = Stub::makeEmpty(TaxAppToStoreFacadeInterface::class, [
            'getStoreByName' => $this->storeTransfer,
        ]);
        $this->tester->mockFactoryMethod('getStoreFacade', $storeFacadeMock);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasTaxTotalWhenRecalculateRequestsTaxFromExternalApiSuccessfully(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertGreaterThanOrEqual(0, $taxCalculationResponseTransfer->getSale()->getTaxTotal());
        foreach ($calculableObjectTransfer->getExpenses() as $expense) {
            $this->assertNotNull($expense->getSumTaxAmount());
            $this->assertNotNull($expense->getUnitTaxAmount());
        }
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasTheSameTaxRequestHashWhenRecalculateWasCalledTwiceWithoutChanges(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $firstCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        $secondCalculationHash = $calculableObjectTransfer->getTaxAppSaleHash();

        $this->assertSame($firstCalculationHash, $secondCalculationHash);
    }

    /**
     * @return void
     */
    public function testCalculableObjectHasTheDifferentTaxRequestHashWhenWasRecalculateCalledTwiceWithChanges(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $clientMock = $this->createMock(TaxAppClient::class);
        $clientMock->expects($this->exactly(2))->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $clientMock);

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
    public function testCalculableObjectHasZeroTaxTotalWhenShipmentIsMissingAndPriceModeIsNet(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransferWithoutShipment($this->storeTransfer);

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
        $this->assertSame(0, $calculableObjectTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testCalculableObjectIsExpandedWithTaxMetadataWhenRecalculateMethodIsCalled(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

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
        $calculableObjectTransfer = $this->tester->haveCalculableObjectTransferWithMerchantStockAddress($this->storeTransfer);

        $taxAppClientMock = $this->makeEmpty(TaxAppClientInterface::class);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $taxAppClientMock->expects($this->once())->method('requestTaxQuotation')->willReturn($taxCalculationResponseTransfer);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

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

        /** @var \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer */
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

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
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);
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
    public function testQuoteHasZeroTaxTotalWhenRecalculateExternalApiRequestFails(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => false]);
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
        $this->tester->assertQuoteHasZeroTaxTotal($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteHasCorrectGrandTotalWhenPriceModeIsGrossAndRecalculateRequestsTaxFromExternalApiSuccessfully(): void
    {
        // Arrange
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, static::PRICE_MODE_GROSS);

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
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'Foo'], false);
        $this->tester->haveTaxAppConfig(['fk_store' => $storeTransfer->getIdStore(), 'is_active' => false]);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertFalse($calculableObjectTransfer->getOriginalQuote()->getHideTaxInCart());
    }

    /**
     * @return void
     */
    public function testCalculateObjectItemsHaveSumTaxAmountWhenStoreIdIsNotProvidedInCalculableObject(): void
    {
        // Arrange
        $storeTransfer = clone $this->storeTransfer;
        $storeTransfer->setIdStore(null);
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($storeTransfer);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true], $calculableObjectTransfer->getItems()->getArrayCopy());
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertSame(
            $taxCalculationResponseTransfer->getSale()->getItems()->offsetGet(0)->getTaxTotal(),
            $calculableObjectTransfer->getItems()->offsetGet(0)->getSumTaxAmount(),
        );
        $this->assertSame(
            $taxCalculationResponseTransfer->getSale()->getItems()->offsetGet(1)->getTaxTotal(),
            $calculableObjectTransfer->getItems()->offsetGet(1)->getSumTaxAmount(),
        );
    }

    /**
     * @return void
     */
    public function testRecalculateWithPriceInGrossModeAppliesExternalResultsCorrectly(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, static::PRICE_MODE_GROSS);
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true], $calculableObjectTransfer->getItems()->getArrayCopy());
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertSame(
            $taxCalculationResponseTransfer->getSale()->getItems()->offsetGet(0)->getTaxTotal(),
            $calculableObjectTransfer->getItems()->offsetGet(0)->getSumTaxAmount(),
        );
        $this->assertSame(
            $taxCalculationResponseTransfer->getSale()->getItems()->offsetGet(1)->getTaxTotal(),
            $calculableObjectTransfer->getItems()->offsetGet(1)->getSumTaxAmount(),
        );
    }

    /**
     * @return void
     */
    public function testRecalculateWithPriceInGrossModeDoesNotHaveHideTaxInCartFlag(): void
    {
        // Arrange
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);
        $this->tester->mockTaxAppClientWithTaxCalculationResponse($taxCalculationResponseTransfer);
        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, static::PRICE_MODE_GROSS);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertEmpty($calculableObjectTransfer->getOriginalQuote()->getHideTaxInCart());
    }

    /**
     * @return void
     */
    public function testRecalculateWithNonConfiguredSellerCountryCodeIsTakenFromDefaultStoreCountry(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getSellerCountryCode', '');
        $expectedCountryCode = $this->storeTransfer->getCountries()[0];
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $taxAppClientMock = $this->createMock(TaxAppClient::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

        // Assert
        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) use ($expectedCountryCode) {
                self::assertSame($expectedCountryCode, $taxCalculationRequestTransfer->getSale()->getSellerCountryCode());

                return true;
            }))
            ->willReturn($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateWithConfiguredSellerCountryCodeIsAppliedToTaxResponse(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getSellerCountryCode', 'FR');
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $taxAppClientMock = $this->createMock(TaxAppClient::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer);

        // Assert
        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) {
                self::assertSame('FR', $taxCalculationRequestTransfer->getSale()->getSellerCountryCode());

                return true;
            }))
            ->willReturn($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateWithNonConfiguredCustomerCountryCodeIsTakenFromDefaultStoreCountry(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getCustomerCountryCode', '');
        $expectedCountryCode = $this->storeTransfer->getCountries()[0];
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $taxAppClientMock = $this->createMock(TaxAppClient::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, 'GROSS_MODE', false);

        // Assert
        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) use ($expectedCountryCode) {
                self::assertSame($expectedCountryCode, $taxCalculationRequestTransfer->getSale()->getCustomerCountryCode());

                return true;
            }))
            ->willReturn($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateWithConfiguredCustomerCountryCodeIsAppliedToTaxResponse(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getCustomerCountryCode', 'FR');
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $taxAppClientMock = $this->createMock(TaxAppClient::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, 'GROSS_MODE', false);

        // Assert
        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) {
                self::assertSame('FR', $taxCalculationRequestTransfer->getSale()->getCustomerCountryCode());

                return true;
            }))
            ->willReturn($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateWithProvidedBillingCountryIsSetToCustomerCountryCodeAppliedToTaxResponse(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getCustomerCountryCode', 'FR');
        $taxCalculationResponseTransfer = $this->tester->haveTaxCalculationResponseTransfer(['isSuccessful' => true]);

        $taxAppClientMock = $this->createMock(TaxAppClient::class);
        $this->tester->mockFactoryMethod('getTaxAppClient', $taxAppClientMock);

        $calculableObjectTransfer = $this->tester->createCalculableObjectTransfer($this->storeTransfer, 'GROSS_MODE', true, ['iso2Code' => 'FOO']);

        // Assert
        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) {
                self::assertSame('FOO', $taxCalculationRequestTransfer->getSale()->getCustomerCountryCode());

                return true;
            }))
            ->willReturn($taxCalculationResponseTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);
    }
}
