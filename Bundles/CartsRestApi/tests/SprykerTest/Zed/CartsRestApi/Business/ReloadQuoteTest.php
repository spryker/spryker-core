<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceCalculatorPlugin;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface;
use Spryker\Zed\PriceCartConnector\Communication\Plugin\CartItemPricePlugin;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApi
 * @group Business
 * @group ReloadQuoteTest
 * Add your own group annotations below this line
 */
class ReloadQuoteTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @uses \Spryker\Shared\PersistentCart\PersistentCartConfig::PERSISTENT_CART_ANONYMOUS_PREFIX
     *
     * @var string
     */
    protected const PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var int
     */
    protected const ORIGINAL_PRODUCT_GROSS_PRICE = 200;

    /**
     * @var int
     */
    protected const UPDATED_PRODUCT_GROSS_PRICE = 100;

    /**
     * @var \SprykerTest\Zed\CartsRestApi\CartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $cartItemPricePlugin = new CartItemPricePlugin();
        $cartItemPricePlugin->setFacade($this->getFacadeWithMockedConfig());
        $this->tester->setDependency(CartDependencyProvider::CART_EXPANDER_PLUGINS, [
            $cartItemPricePlugin,
        ]);
        $this->tester->setDependency(CalculationDependencyProvider::QUOTE_CALCULATOR_PLUGIN_STACK, [
            new PriceCalculatorPlugin(),
        ]);
        /*
         * There is a current Store in context of RestApi usage
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => 'DE']));
    }

    /**
     * @return void
     */
    public function testFindQuoteByUuidWithQuoteItemReload(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $customerTransfer = $this->tester->haveCustomer();

        $priceProductTransfer = $this->createPriceProductTransfer($productConcreteTransfer, static::ORIGINAL_PRODUCT_GROSS_PRICE);
        $quoteTransfer = $this->createQuote($customerTransfer, $productConcreteTransfer);

        // Act
        $this->updatePriceProduct($priceProductTransfer, static::UPDATED_PRODUCT_GROSS_PRICE);
        $quoteResponseTransfer = $this->tester->getFacade()->findQuoteByUuidWithQuoteItemReload($quoteTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);
        $this->assertSame(static::UPDATED_PRODUCT_GROSS_PRICE, $itemTransfer->getSumGrossPriceOrFail());
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillReloadQuote(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $customerTransfer = $this->tester->haveCustomer();

        $priceProductTransfer = $this->createPriceProductTransfer($productConcreteTransfer, static::ORIGINAL_PRODUCT_GROSS_PRICE);
        $quoteTransfer = $this->createQuote($customerTransfer, $productConcreteTransfer);

        $updatedQuantity = 5;
        $cartItemChangeTransfer = (new CartItemRequestTransfer())
            ->setSku($productConcreteTransfer->getSkuOrFail())
            ->setCustomer($customerTransfer)
            ->setQuoteUuid($quoteTransfer->getUuidOrFail())
            ->setQuantity($updatedQuantity);

        // Act
        $this->updatePriceProduct($priceProductTransfer, static::UPDATED_PRODUCT_GROSS_PRICE);
        $quoteResponseTransfer = $this->tester->getFacade()->updateItemQuantity($cartItemChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);

        $this->assertSame(static::UPDATED_PRODUCT_GROSS_PRICE * $updatedQuantity, $itemTransfer->getSumGrossPriceOrFail());
    }

    /**
     * @return void
     */
    public function testAddToCartWillReloadQuote(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $customerTransfer = $this->tester->haveCustomer();

        $priceProductTransfer = $this->createPriceProductTransfer($productConcreteTransfer, static::ORIGINAL_PRODUCT_GROSS_PRICE);
        $quoteTransfer = $this->createQuote($customerTransfer, $productConcreteTransfer);

        $newProductConcreteTransfer = $this->tester->haveProduct();
        $this->createPriceProductTransfer($newProductConcreteTransfer, random_int(1, 999));

        $cartItemChangeTransfer = (new CartItemRequestTransfer())
            ->setSku($newProductConcreteTransfer->getSkuOrFail())
            ->setCustomer($customerTransfer)
            ->setQuantity(1)
            ->setQuoteUuid($quoteTransfer->getUuidOrFail());

        // Act
        $this->updatePriceProduct($priceProductTransfer, static::UPDATED_PRODUCT_GROSS_PRICE);
        $quoteResponseTransfer = $this->tester->getFacade()->addToCart($cartItemChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);

        $this->assertSame($productConcreteTransfer->getSkuOrFail(), $itemTransfer->getSku());
        $this->assertSame(static::UPDATED_PRODUCT_GROSS_PRICE, $itemTransfer->getSumGrossPriceOrFail());
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillReloadQuote(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $customerTransfer = (new CustomerTransfer())->setCustomerReference(static::PERSISTENT_CART_ANONYMOUS_PREFIX . uniqid());

        $priceProductTransfer = $this->createPriceProductTransfer($productConcreteTransfer, static::ORIGINAL_PRODUCT_GROSS_PRICE);
        $quoteTransfer = $this->createQuote($customerTransfer, $productConcreteTransfer);

        $newProductConcreteTransfer = $this->tester->haveProduct();
        $this->createPriceProductTransfer($newProductConcreteTransfer, random_int(1, 999));

        $cartItemChangeTransfer = (new CartItemRequestTransfer())
            ->setSku($newProductConcreteTransfer->getSkuOrFail())
            ->setCustomer($customerTransfer)
            ->setQuantity(1)
            ->setQuoteUuid($quoteTransfer->getUuidOrFail());

        // Act
        $this->updatePriceProduct($priceProductTransfer, static::UPDATED_PRODUCT_GROSS_PRICE);
        $quoteResponseTransfer = $this->tester->getFacade()->addToGuestCart($cartItemChangeTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0);

        $this->assertSame($productConcreteTransfer->getSkuOrFail(), $itemTransfer->getSku());
        $this->assertSame(static::UPDATED_PRODUCT_GROSS_PRICE, $itemTransfer->getSumGrossPriceOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $grossAmount
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(ProductConcreteTransfer $productConcreteTransfer, int $grossAmount): PriceProductTransfer
    {
        return $this->tester->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcreteOrFail(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSkuOrFail(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSkuOrFail(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::GROSS_AMOUNT => $grossAmount,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $updatedGrossPrice
     *
     * @return void
     */
    protected function updatePriceProduct(PriceProductTransfer $priceProductTransfer, int $updatedGrossPrice): void
    {
        $priceProductEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProductOrFail())
            ->filterByFkStore($priceProductTransfer->getMoneyValue()->getFkStoreOrFail())
            ->filterByFkCurrency($priceProductTransfer->getMoneyValue()->getFkCurrencyOrFail())
            ->findOne();

        $priceProductEntity
            ->setGrossPrice($updatedGrossPrice)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(CustomerTransfer $customerTransfer, ProductConcreteTransfer $productConcreteTransfer): QuoteTransfer
    {
        return $this->tester->havePersistentQuote([
            QuoteTransfer::PRICE_MODE => static::PRICE_GROSS_MODE,
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productConcreteTransfer->getSku(),
                    ItemTransfer::QUANTITY => 1,
                ],
            ],
        ]);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function getFacadeWithMockedConfig(): PriceCartConnectorFacadeInterface
    {
        $priceCartConnectorFacade = new PriceCartConnectorFacade();
        $configMock = $this->createMock(PriceCartConnectorConfig::class);
        $configMock->method('getItemFieldsForIdentifier')
            ->willReturn([
                ItemTransfer::SKU,
                ItemTransfer::QUANTITY,
                ItemTransfer::MERCHANT_REFERENCE,
                ItemTransfer::PRODUCT_OFFER_REFERENCE,
            ]);
        $configMock->method('getItemFieldsForIsSameItemComparison')
            ->willReturn([
                ItemTransfer::SKU,
                ItemTransfer::MERCHANT_REFERENCE,
                ItemTransfer::PRODUCT_OFFER_REFERENCE,
            ]);

        $priceCartConnectorBusinessFactory = new PriceCartConnectorBusinessFactory();
        $priceCartConnectorBusinessFactory->setConfig($configMock);

        $priceCartConnectorFacade->setFactory($priceCartConnectorBusinessFactory);

        return $priceCartConnectorFacade;
    }
}
