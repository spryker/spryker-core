<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
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
                foreach ($taxCalculationRequestTransfer->getSale()->getItems() as $index => $saleItem) {
                    $saleItemExpectation = $expectation[$index];

                    self::assertEquals(
                        $saleItemExpectation['quantity'],
                        $saleItem->getQuantity(),
                        'Sale item quantity must match with Calculated Object merchant stock address',
                    );

                    self::assertEquals(
                        $saleItemExpectation['warehouseAddress']['address1'],
                        $saleItem->getWarehouseAddress()->getAddress1(),
                        'Sale item address must match with Calculated Object merchant stock address',
                    );

                    self::assertEquals(
                        $saleItemExpectation['warehouseAddress']['city'],
                        $saleItem->getWarehouseAddress()->getCity(),
                        'Sale item city must match with Calculated Object merchant stock address',
                    );

                    self::assertEquals(
                        $saleItemExpectation['warehouseAddress']['zip_code'],
                        $saleItem->getWarehouseAddress()->getZipCode(),
                        'Sale item zip code must match with Calculated Object merchant stock address',
                    );

                    self::assertIsString(
                        $saleItem->getWarehouseAddress()->getCountry(),
                        'Sale item country must be a string',
                    );

                    self::assertTrue(
                        strlen($saleItem->getWarehouseAddress()->getCountry()) == 2,
                        'Sale item country code must be a string with 2 characters',
                    );
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

                    self::assertNull(
                        $saleItem->getWarehouseAddress(),
                        'Sale item must have its saller physical address null',
                    );
                }

                return true;
            }));
    }

    /**
     * @return array[]
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
}
