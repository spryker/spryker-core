<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

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
     * @uses \Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidator::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CART_PRE_CHECK_PRICE_FAILED = 'cart.pre.check.price.failed';

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
        $filteredQuoteTransfer = $this->tester->getFacade()->filterItemsWithoutPrice($quoteTransfer);

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
     * @return void
     */
    public function testValidatePricesWithNonZeroPriceAndDisabledZeroPriceConfig(): void
    {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(static::TEST_SKU_1, 1000, false);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $messages = $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer);
        $this->assertSame([], $messages);
    }

    /**
     * @return void
     */
    public function testValidatePricesWithZeroPriceAndDisabledZeroPriceConfig(): void
    {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(static::TEST_SKU_1, 0, false);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $messages = $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer);
        $this->assertSame([static::GLOSSARY_KEY_CART_PRE_CHECK_PRICE_FAILED], $messages);
    }

    /**
     * @return void
     */
    public function testValidatePricesWithNonZeroPriceAndEnabledZeroPriceConfig(): void
    {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(static::TEST_SKU_1, 1000, true);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $messages = $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer);
        $this->assertSame([], $messages);
    }

    /**
     * @return void
     */
    public function testValidatePricesWithZeroPriceAndEnabledZeroPriceConfig(): void
    {
        // Arrange
        $priceCartConnectorFacade = $this->getConfiguredPriceCartConnectorFacade(static::TEST_SKU_1, 0, true);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItem();

        // Act
        $cartPreCheckResponseTransfer = $priceCartConnectorFacade->validatePrices($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $messages = $this->tester->getCartPreCheckResponseTransferMessages($cartPreCheckResponseTransfer);
        $this->assertSame([], $messages);
    }

    /**
     * @param string $sku
     * @param int $price
     * @param bool $isZeroPriceEnabledForCartActions
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function getConfiguredPriceCartConnectorFacade(
        string $sku,
        int $price,
        bool $isZeroPriceEnabledForCartActions
    ): PriceCartConnectorFacadeInterface {
        $priceCartConnectorConfigMock = $this->getPriceCartConnectorConfigMock();
        $priceProductFacadeStub = $this->tester->createPriceProductFacadeStub();
        $priceProductFacadeStub->addPriceStub($sku, $price);
        $priceFacadeMock = $this->getPriceFacadeMock();
        $currencyFacadeMock = $this->getCurrencyFacadeBridgeMock();

        return $this->tester->createAndConfigurePriceCartConnectorFacade(
            $priceCartConnectorConfigMock,
            $priceProductFacadeStub,
            $priceFacadeMock,
            $currencyFacadeMock,
            $isZeroPriceEnabledForCartActions,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function getPriceFacadeMock(): PriceCartToPriceInterface
    {
        return $this->getMockBuilder(PriceCartToPriceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected function getCurrencyFacadeBridgeMock(): PriceCartConnectorToCurrencyFacadeInterface
    {
        return $this->getMockBuilder(PriceCartConnectorToCurrencyFacadeInterface::class)->getMock();
    }

    /**
     * /**
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected function getPriceCartConnectorConfigMock(): PriceCartConnectorConfig
    {
        return $this->getMockBuilder(PriceCartConnectorConfig::class)->getMock();
    }
}
