<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Client\TaxApp\TaxAppClientInterface;

class TaxAppBusinessAssertionHelper extends Module
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function assertCalculableObjectTransferExtendedWithTaxMetadata(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->assertNotNull($calculableObjectTransfer->getTaxMetadata());
        $this->assertNotNull($calculableObjectTransfer->getItems()[0]->getTaxMetadata());
    }

    /**
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClientMock
     *
     * @return void
     */
    public function assertRequestTaxQuotationReceivesSalesItemMappedWithMerchantStockAddress(TaxAppClientInterface $taxAppClientMock): void
    {
        $expectation = $this->haveExpectedTaxQuotationRequestSaleItems();

        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) use ($expectation) {
                $index = 0;

                foreach ($taxCalculationRequestTransfer->getSale()->getItems() as $saleItem) {
                    foreach ($saleItem->getShippingWarehouses() as $quantityWarehouseMap) {
                        $saleItemExpectation = $expectation[$index++];

                        self::assertEquals(
                            $saleItemExpectation['quantity'],
                            $quantityWarehouseMap->getQuantity(),
                            'Warehouse mapping quantity must match with Calculated Object merchant stock address',
                        );

                        self::assertEquals(
                            $saleItemExpectation['warehouseAddress']['address1'],
                            $quantityWarehouseMap->getWarehouseAddress()->getAddress1(),
                            'Warehouse mapping address must match with Calculated Object merchant stock address',
                        );

                        self::assertEquals(
                            $saleItemExpectation['warehouseAddress']['city'],
                            $quantityWarehouseMap->getWarehouseAddress()->getCity(),
                            'Warehouse mapping city must match with Calculated Object merchant stock address',
                        );

                        self::assertEquals(
                            $saleItemExpectation['warehouseAddress']['zip_code'],
                            $quantityWarehouseMap->getWarehouseAddress()->getZipCode(),
                            'Warehouse mapping zip code must match with Calculated Object merchant stock address',
                        );

                        self::assertIsString(
                            $quantityWarehouseMap->getWarehouseAddress()->getCountry(),
                            'Warehouse mapping country must be a string',
                        );

                        self::assertTrue(
                            strlen($quantityWarehouseMap->getWarehouseAddress()->getCountry()) == 2,
                            'Warehouse mapping country code must be a string with 2 characters',
                        );
                    }
                }

                return true;
            }));
    }

    /**
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClientMock
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $mockedCalculableObjectTransfer
     *
     * @return void
     */
    public function assertRequestTaxQuotationReceivesSalesItemWithCorrectItemsAndWithoutWarehouseAddress(
        TaxAppClientInterface $taxAppClientMock,
        CalculableObjectTransfer $mockedCalculableObjectTransfer
    ): void {
        $expectation = $this->haveExpectedTaxQuotationRequestSaleItems();

        $taxAppClientMock->expects(new InvokedCountMatcher(1))
            ->method('requestTaxQuotation')
            ->with(new Callback(function (TaxCalculationRequestTransfer $taxCalculationRequestTransfer) use ($mockedCalculableObjectTransfer) {
                self::assertEquals(
                    $mockedCalculableObjectTransfer->getItems()->count(),
                    $taxCalculationRequestTransfer->getSale()->getItems()->count(),
                    'Sale items count must match with Calculated Object',
                );

                foreach ($taxCalculationRequestTransfer->getSale()->getItems() as $index => $saleItem) {
                    /** @var \SprykerTest\Zed\TaxApp\Helper\ItemTransfer $calculableObjectItem */
                    $calculableObjectItem = $mockedCalculableObjectTransfer->getItems()->offsetGet($index);

                    self::assertEquals(
                        $calculableObjectItem->getQuantity(),
                        $saleItem->getQuantity(),
                        'Sale item quantity must match with Calculated Object',
                    );
                }

                return true;
            }));
    }

    /**
     * @return array<array>
     */
    protected function haveExpectedTaxQuotationRequestSaleItems(): array
    {
        return [
            [
                'quantity' => 3,
                'warehouseAddress' => [
                    'address1' => 'address-1-1',
                    'city' => 'city-1-1',
                    'zip_code' => 'zipcode-1-1',
                ],
            ],
            [
                'quantity' => 1,
                'warehouseAddress' => [
                    'address1' => 'address-1-2',
                    'city' => 'city-1-2',
                    'zip_code' => 'zipcode-1-2',
                ],
            ],
            [
                'quantity' => 10,
                'warehouseAddress' => [
                    'address1' => 'address-2-1',
                    'city' => 'city-2-1',
                    'zip_code' => 'zipcode-2-1',
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function assertQuoteHasCorrectGrandTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $itemsSumPriceToPayAggregation = 0;
        foreach ($calculableObjectTransfer->getItems() as $item) {
            $itemsSumPriceToPayAggregation += $item->getSumPriceToPayAggregation();
        }

        $expensesSumPriceToPayAggregation = 0;

        foreach ($calculableObjectTransfer->getExpenses() as $expense) {
            $expensesSumPriceToPayAggregation += $expense->getSumPriceToPayAggregation();
        }

        $grandTotal = $calculableObjectTransfer->getOriginalQuote()->getTotals()->getGrandTotal();

        $this->assertEquals($grandTotal, ($expensesSumPriceToPayAggregation + $itemsSumPriceToPayAggregation));
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param string $apiUrl
     *
     * @return void
     */
    public function assertAllTaxAppConfigsForTenantHaveNewApiUrl(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        string $apiUrl
    ): void {
        $taxAppConfigEntityCollection = SpyTaxAppConfigQuery::create()
            ->filterByVendorCode($taxAppConfigTransfer->getVendorCode())
            ->find();

        $this->assertTrue($taxAppConfigEntityCollection->count() > 1);

        foreach ($taxAppConfigEntityCollection as $taxAppConfigEntity) {
            $this->assertEquals($taxAppConfigEntity->getApiUrl(), $apiUrl);
        }
    }

    /**
     * @param string $vendorCode
     *
     * @return void
     */
    public function assertAllTaxAppConfigsForTenantHaveBeenDeleted(string $vendorCode): void
    {
        $taxAppConfigEntityCollectionDeleted = SpyTaxAppConfigQuery::create()
            ->filterByVendorCode($vendorCode)
            ->find();

        $this->assertTrue($taxAppConfigEntityCollectionDeleted->count() == 0);
    }

    /**
     * @param string $vendorCodeNotDeleted
     * @param string $vendorCodeDeleted
     *
     * @return void
     */
    public function assertProperTaxAppConfigsHaveBeenDeletedByVendorCodes(string $vendorCodeNotDeleted, string $vendorCodeDeleted): void
    {
        $deletedTaxAppConfigEntityCollection = SpyTaxAppConfigQuery::create()
            ->filterByVendorCode($vendorCodeDeleted)
            ->find();

        $notDeletedTaxAppConfigEntityCollection = SpyTaxAppConfigQuery::create()
            ->filterByVendorCode($vendorCodeNotDeleted)
            ->find();

        $this->assertTrue($deletedTaxAppConfigEntityCollection->count() == 0);
        $this->assertTrue($notDeletedTaxAppConfigEntityCollection->count() > 0);
    }
}
