<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Filter;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparator;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilter;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Filter
 * @group PriceProductFilterTest
 * Add your own group annotations below this line
 */
class PriceProductFilterTest extends Unit
{
    protected const CART_OPERATION_ADD = 'add';

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOnlyMergeItemsWithAllMatchingFields(): void
    {
        // Arrange
        $skuToMerge = 5;
        $merchantReferenceToMerge = 'ME--001';

        $cartChangeTransfer = (new CartChangeBuilder([
            CartChangeTransfer::OPERATION => static::CART_OPERATION_ADD,
        ]))
            ->withItem([
                ItemTransfer::SKU => $skuToMerge,
                ItemTransfer::MERCHANT_REFERENCE => $merchantReferenceToMerge,
                ItemTransfer::QUANTITY => 2,
            ])
            ->withItem([
                ItemTransfer::SKU => $skuToMerge,
                ItemTransfer::MERCHANT_REFERENCE => $merchantReferenceToMerge,
                ItemTransfer::QUANTITY => 5,
            ])
            ->withItem([
                ItemTransfer::SKU => $skuToMerge,
                ItemTransfer::MERCHANT_REFERENCE => 'ME--999',
                ItemTransfer::QUANTITY => 3,
            ])
            ->withItem([
                ItemTransfer::SKU => 999,
                ItemTransfer::MERCHANT_REFERENCE => 'ME--999',
                ItemTransfer::QUANTITY => 3,
            ])
            ->build();

        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => 'DEFAULT',
        ]))
            ->withCurrency()
            ->withItem([
                ItemTransfer::SKU => $skuToMerge,
                ItemTransfer::MERCHANT_REFERENCE => $merchantReferenceToMerge,
                ItemTransfer::QUANTITY => 7,
            ])
            ->withItem([
                ItemTransfer::SKU => $skuToMerge,
                ItemTransfer::MERCHANT_REFERENCE => 'ME--999',
                ItemTransfer::QUANTITY => 3,
            ])
            ->withItem([
                ItemTransfer::SKU => 999,
                ItemTransfer::MERCHANT_REFERENCE => 'ME--999',
                ItemTransfer::QUANTITY => 3,
            ])
            ->build();

        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $skuToMerge,
            ItemTransfer::MERCHANT_REFERENCE => $merchantReferenceToMerge,
        ]))->build();

        $itemFieldsForIsSameItemComparison = [
            ItemTransfer::SKU,
            ItemTransfer::MERCHANT_REFERENCE,
        ];
        $priceProductFilter = $this->createPriceProductFilter($itemFieldsForIsSameItemComparison);

        //Act
        $priceProductFilterTransfer = $priceProductFilter
            ->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);

        //Assert
        $this->assertEquals(
            14,
            $priceProductFilterTransfer->getQuantity(),
            'Expects that only items with all matching fields (from config) are merged.'
        );
        $this->assertEquals($skuToMerge, $priceProductFilterTransfer->getSku());
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilter
     */
    protected function createPriceProductFilter(array $itemFieldsForIsSameItemComparison): PriceProductFilter
    {
        return new PriceProductFilter(
            $this->createPriceProductFacadeMock(),
            $this->createPriceFacadeMock(),
            $this->createCurrencyFacadeMock(),
            $this->createCartItemQuantityCounterStrategyPlugins(),
            $this->createItemComparator($itemFieldsForIsSameItemComparison)
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected function createPriceProductFacadeMock(): PriceCartToPriceProductInterface
    {
        return $this->getMockBuilder(PriceCartToPriceProductInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function createPriceFacadeMock(): PriceCartToPriceInterface
    {
        return $this->getMockBuilder(PriceCartToPriceInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected function createCurrencyFacadeMock(): PriceCartConnectorToCurrencyFacadeInterface
    {
        return $this->getMockBuilder(PriceCartConnectorToCurrencyFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    protected function createCartItemQuantityCounterStrategyPlugins()
    {
        return [];
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface
     */
    protected function createItemComparator(
        array $itemFieldsForIsSameItemComparison
    ): ItemComparatorInterface {
        return new ItemComparator(
            $this->createPriceCartConnectorConfigMock($itemFieldsForIsSameItemComparison)
        );
    }

    /**
     * @param string[] $itemFieldsForIsSameItemComparison
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected function createPriceCartConnectorConfigMock(array $itemFieldsForIsSameItemComparison): PriceCartConnectorConfig
    {
        $priceCartConnectorConfigMock = $this->getMockBuilder(PriceCartConnectorConfig::class)->getMock();
        $priceCartConnectorConfigMock
            ->method('getItemFieldsForIsSameItemComparison')
            ->willReturn($itemFieldsForIsSameItemComparison);

        return $priceCartConnectorConfigMock;
    }
}
