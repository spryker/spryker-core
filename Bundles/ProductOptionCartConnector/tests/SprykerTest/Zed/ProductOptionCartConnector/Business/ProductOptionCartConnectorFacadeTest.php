<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionBridge;
use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionCartConnector
 * @group Business
 * @group Facade
 * @group ProductOptionCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionCartConnectorFacadeTest extends Unit
{
    const ID_PRODUCT_OPTION = 5;

    /**
     * @var \SprykerTest\Zed\ProductOptionCartConnector\ProductOptionCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacadeInterface
     */
    public function getProductOptionCartConnectorFacade()
    {
        return new ProductOptionCartConnectorFacade();
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        return $this->tester->getLocator()->price()->facade()->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        return $this->tester->getLocator()->price()->facade()->getNetPriceModeIdentifier();
    }

    /**
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer($priceMode)
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode($priceMode);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithDefaultItem($priceMode)
    {
        $cartChangeTransfer = $this->createCartChangeTransfer($priceMode);
        $this->addItemToCart($cartChangeTransfer, new ArrayObject([
            (new ProductOptionValueTransfer())->setIdProductOptionValue(static::ID_PRODUCT_OPTION),
        ]));

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cart
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionCollection
     *
     * @return void
     */
    protected function addItemToCart(CartChangeTransfer $cart, ArrayObject $productOptionCollection)
    {
        $item = new ItemTransfer();
        $item->setProductOptions($productOptionCollection);

        $cart->addItem($item);
    }

    /**
     * @uses ProductOptionFacadeInterface::getProductOptionValue()
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function mockProductOptionFacade(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionFacadeMock = $this->getMockBuilder(ProductOptionCartConnectorToProductOptionBridge::class)
            ->setMethods(['getProductOptionValue'])
            ->disableOriginalConstructor()
            ->getMock();

        $productOptionFacadeMock->expects($this->any())
            ->method('getProductOptionValue')
            ->willReturn($productOptionTransfer);

        $this->tester->setDependency(
            ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION,
            $productOptionFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsSanitizesNetPriceWhenGrossPriceModeIsActive()
    {
        // Assign
        $this->mockProductOptionFacade((new ProductOptionTransfer())->setIdProductOptionValue(static::ID_PRODUCT_OPTION));

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getGrossPriceModeIdentifier());
        $expectedResult = 0;

        // Act
        $actualResult = $this->getProductOptionCartConnectorFacade()->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult->getItems()[0]->getProductOptions()[0]->getUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsSanitizesGrossPriceWhenNetPriceModeIsActive()
    {
        // Assign
        $this->mockProductOptionFacade((new ProductOptionTransfer())->setIdProductOptionValue(static::ID_PRODUCT_OPTION));

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getNetPriceModeIdentifier());
        $expectedResult = 0;

        // Act
        $actualResult = $this->getProductOptionCartConnectorFacade()->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult->getItems()[0]->getProductOptions()[0]->getUnitGrossPrice());
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsExpandsProductOptions()
    {
        // Assign
        $expectedResult = (new ProductOptionTransfer())
            ->setIdProductOptionValue(static::ID_PRODUCT_OPTION)
            ->setGroupName('test')
            ->setUnitGrossPrice(0);
        $this->mockProductOptionFacade(clone $expectedResult);

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getNetPriceModeIdentifier());

        // Act
        $actualResult = $this->getProductOptionCartConnectorFacade()->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult->toArray(), $actualResult->getItems()[0]->getProductOptions()[0]->toArray());
    }
}
