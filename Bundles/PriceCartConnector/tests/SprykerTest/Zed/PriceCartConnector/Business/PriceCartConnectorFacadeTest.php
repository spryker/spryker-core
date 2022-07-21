<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Facade
 * @group PriceCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class PriceCartConnectorFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'TEST_SKU_1';

    /**
     * @var string
     */
    protected const TEST_SKU_2 = 'TEST_SKU_2';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_1 = 'TCF';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_2 = 'TCS';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_3 = 'TCT';

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider getFilterItemsWithoutPriceDataProvider
     *
     * @param array $itemsData
     * @param string $currencyCode
     * @param array<string> $expectedSkus
     *
     * @return void
     */
    public function testFilterItemsWithoutPriceWillRemoveItemsWithoutPrices(array $itemsData, string $currencyCode, array $expectedSkus): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => $currencyCode]);
        $quoteTransfer = $this->tester->createQuoteWithItems($itemsData, $currencyTransfer);

        // Act
        $filteredQuoteTransfer = $this->createPriceCartConnectorFacade()->filterItemsWithoutPrice($quoteTransfer);

        // Assert
        $itemsSkus = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getSku();
        }, $filteredQuoteTransfer->getItems()->getArrayCopy());

        $this->assertSame($expectedSkus, $itemsSkus);
    }

    /**
     * @return array
     */
    public function getFilterItemsWithoutPriceDataProvider(): array
    {
        return [
            [
                [
                    static::TEST_SKU_1 => 100,
                    static::TEST_SKU_2 => 0,
                ],
                static::TEST_CURRENCY_1,
                [
                    static::TEST_SKU_1,
                    static::TEST_SKU_2,
                ],
            ],
            [
                [
                    static::TEST_SKU_1 => 300,
                    static::TEST_SKU_2 => null,
                ],
                static::TEST_CURRENCY_2,
                [
                    static::TEST_SKU_1,
                ],
            ],
            [
                [
                    static::TEST_SKU_1 => null,
                    static::TEST_SKU_2 => null,
                ],
                static::TEST_CURRENCY_3,
                [],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function createPriceCartConnectorFacade(): PriceCartConnectorFacadeInterface
    {
        return new PriceCartConnectorFacade();
    }
}
