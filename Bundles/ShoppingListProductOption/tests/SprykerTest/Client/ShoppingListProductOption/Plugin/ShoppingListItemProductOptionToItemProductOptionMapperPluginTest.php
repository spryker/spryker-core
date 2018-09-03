<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOption\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface;
use Spryker\Client\ShoppingListProductOption\Plugin\ShoppingListItemProductOptionToItemProductOptionMapperPlugin;
use Spryker\Client\ShoppingListProductOption\ShoppingListProductOptionDependencyProvider;
use Spryker\Client\ShoppingListProductOption\ShoppingListProductOptionFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ShoppingListProductOption
 * @group Plugin
 * @group ShoppingListItemProductOptionToItemProductOptionMapperPluginTest
 * Add your own group annotations below this line
 */
class ShoppingListItemProductOptionToItemProductOptionMapperPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ShoppingListProductOption\ShoppingListProductOptionClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemNotInCart(): void
    {
        // Prepare
        $productTransfer = $this->tester->haveProduct();
        $productOptionValueTransfer = $this->tester->createProductOptionGroupValueTransfer($productTransfer->getSku());
        $productOptionTransfer = (new ProductOptionTransfer())->setValue($productOptionValueTransfer);
        $itemTransfer = (new ItemTransfer())->setSku('sku_sample');
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->addProductOption($productOptionTransfer);

        $container = new Container();
        $cartMock = $this->getCartMock();

        $cartMock->method('getQuote')
            ->will($this->returnValue(new QuoteTransfer()));
        $cartMock->method('findQuoteItem')
            ->will($this->returnValue(null));

        $container[ShoppingListProductOptionDependencyProvider::CLIENT_CART] = function (Container $container) use ($cartMock) {
            return $cartMock;
        };

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->getShoppingListItemProductOptionToItemProductOptionMapperPlugin($container);
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        $this->assertContains($productOptionTransfer, $actualResult->getProductOptions());
    }

    /**
     * @return void
     */
    public function testMapShoppingListItemProductOptionToItemProductOptionWithItemInCart(): void
    {
        // Prepare
        $sku = 'sku_sample';
        $groupKey = 'sample_group_key';
        $productTransfer = $this->tester->haveProduct();
        $productOptionValueTransfer = $this->tester->createProductOptionGroupValueTransfer($productTransfer->getSku());
        $productOptionTransfer = (new ProductOptionTransfer())->setValue($productOptionValueTransfer);
        $itemTransfer = (new ItemTransfer())->setSku($sku);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku($sku)
            ->addProductOption($productOptionTransfer);

        $container = new Container();
        $cartMock = $this->getCartMock();

        $itemTransferInCart = (new ItemTransfer())
            ->setSku($sku)
            ->addProductOption($productOptionTransfer)
            ->setGroupKey($groupKey);

        $cartMock->method('getQuote')
            ->will($this->returnValue((new QuoteTransfer())->addItem($itemTransferInCart)));
        $cartMock->method('findQuoteItem')
            ->will($this->returnValue($itemTransferInCart));

        $container[ShoppingListProductOptionDependencyProvider::CLIENT_CART] = function (Container $container) use ($cartMock) {
            return $cartMock;
        };

        // Action
        $shoppingListItemProductOptionMapperPlugin = $this->getShoppingListItemProductOptionToItemProductOptionMapperPlugin($container);
        $actualResult = $shoppingListItemProductOptionMapperPlugin->map(
            $shoppingListItemTransfer,
            $itemTransfer
        );

        // Assert
        $this->assertCount(1, $actualResult->getProductOptions());
        $this->assertContains($productOptionTransfer, $actualResult->getProductOptions());
        $this->assertSame($actualResult->getGroupKey(), $groupKey);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface
     */
    protected function getCartMock()
    {
        return $this->getMockBuilder(ShoppingListProductOptionToCartClientInterface::class)->setMethods([
            'getQuote',
            'findQuoteItem',
        ])->disableOriginalConstructor()->getMock();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\ShoppingListProductOption\Plugin\ShoppingListItemProductOptionToItemProductOptionMapperPlugin
     */
    protected function getShoppingListItemProductOptionToItemProductOptionMapperPlugin(Container $container): ShoppingListItemProductOptionToItemProductOptionMapperPlugin
    {
        $shoppingListItemProductOptionClientFactory = new ShoppingListProductOptionFactory();
        $shoppingListItemProductOptionClientFactory->setContainer($container);

        $shoppingListItemProductOptionMapperPlugin = new ShoppingListItemProductOptionToItemProductOptionMapperPlugin();
        $shoppingListItemProductOptionMapperPlugin->setFactory($shoppingListItemProductOptionClientFactory);

        return $shoppingListItemProductOptionMapperPlugin;
    }
}
