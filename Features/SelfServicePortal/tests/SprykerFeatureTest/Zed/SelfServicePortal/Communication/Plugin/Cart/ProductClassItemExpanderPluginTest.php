<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Cart\ProductClassItemExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ProductClassItemExpanderPluginTest
 */
class ProductClassItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'test-sku';

    /**
     * @var array<string>
     */
    protected const TEST_PRODUCT_CLASS_NAMES = ['test-class-1', 'test-class-2'];

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandItemsExpandsItemsWithProductClasses(): void
    {
        // Arrange
        $productClassExpanderMock = $this->createProductClassExpanderMock();

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        $productClassExpanderMock->expects($this->once())
            ->method('expandItems')
            ->with($cartChangeTransfer)
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
                    if ($itemTransfer->getSku() === static::TEST_PRODUCT_SKU) {
                        $productClasses = new ArrayObject();
                        foreach (static::TEST_PRODUCT_CLASS_NAMES as $className) {
                            $productClassTransfer = new ProductClassTransfer();
                            $productClassTransfer->setName($className);
                            $productClasses->append($productClassTransfer);
                        }
                        $itemTransfer->setProductClasses($productClasses);
                    }
                }

                return $cartChangeTransfer;
            });

        $productClassItemExpanderPlugin = $this->createProductClassItemExpanderPluginWithMock($productClassExpanderMock);

        // Act
        $resultCartChangeTransfer = $productClassItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $this->assertNotNull($itemTransfer->getProductClasses());
        $this->assertCount(count(static::TEST_PRODUCT_CLASS_NAMES), $itemTransfer->getProductClasses());
        $this->assertEquals(static::TEST_PRODUCT_CLASS_NAMES[0], $itemTransfer->getProductClasses()[0]->getName());
        $this->assertEquals(static::TEST_PRODUCT_CLASS_NAMES[1], $itemTransfer->getProductClasses()[1]->getName());
    }

    public function testExpandItemsDoesNothingWhenNoItemsProvided(): void
    {
        // Arrange
        $productClassExpanderMock = $this->createProductClassExpanderMock();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        $productClassExpanderMock->expects($this->once())
            ->method('expandItems')
            ->with($cartChangeTransfer)
            ->willReturn($cartChangeTransfer);

        $productClassItemExpanderPlugin = $this->createProductClassItemExpanderPluginWithMock($productClassExpanderMock);

        // Act
        $resultCartChangeTransfer = $productClassItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $resultCartChangeTransfer->getItems());
    }

    public function testExpandItemsDoesNothingWhenRepositoryReturnsNoResults(): void
    {
        // Arrange
        $productClassExpanderMock = $this->createProductClassExpanderMock();

        $cartChangeTransfer = $this->createCartChangeTransferWithItem();

        $productClassExpanderMock->expects($this->once())
            ->method('expandItems')
            ->with($cartChangeTransfer)
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
                    $itemTransfer->setProductClasses(new ArrayObject());
                }

                return $cartChangeTransfer;
            });

        $productClassItemExpanderPlugin = $this->createProductClassItemExpanderPluginWithMock($productClassExpanderMock);

        // Act
        $resultCartChangeTransfer = $productClassItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $productClasses = $itemTransfer->getProductClasses();
        $this->assertInstanceOf(ArrayObject::class, $productClasses);
        $this->assertCount(0, $productClasses);
    }

    public function testExpandItemsDoesNothingWhenNoSkuProvided(): void
    {
        // Arrange
        $productClassExpanderMock = $this->createProductClassExpanderMock();

        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer(); // Item with no SKU
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        $productClassExpanderMock->expects($this->once())
            ->method('expandItems')
            ->with($cartChangeTransfer)
            ->willReturnCallback(function (CartChangeTransfer $cartChangeTransfer) {
                foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
                    $itemTransfer->setProductClasses(new ArrayObject());
                }

                return $cartChangeTransfer;
            });

        $productClassItemExpanderPlugin = $this->createProductClassItemExpanderPluginWithMock($productClassExpanderMock);

        // Act
        $resultCartChangeTransfer = $productClassItemExpanderPlugin->expandItems($cartChangeTransfer);

        // Assert
        $itemTransfer = $resultCartChangeTransfer->getItems()[0];
        $productClasses = $itemTransfer->getProductClasses();
        $this->assertInstanceOf(ArrayObject::class, $productClasses);
        $this->assertCount(0, $productClasses);
    }

    protected function createProductClassItemExpanderPluginWithMock(
        ProductClassExpanderInterface $productClassExpanderMock
    ): ProductClassItemExpanderPlugin {
        $businessFactoryMock = $this->getMockBuilder(SelfServicePortalBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $businessFactoryMock->expects($this->once())
            ->method('createProductClassExpander')
            ->willReturn($productClassExpanderMock);

        $productClassItemExpanderPlugin = new ProductClassItemExpanderPlugin();
        $productClassItemExpanderPlugin->setBusinessFactory($businessFactoryMock);

        return $productClassItemExpanderPlugin;
    }

    protected function createCartChangeTransferWithItem(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(static::TEST_PRODUCT_SKU);

        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassExpanderInterface
     */
    protected function createProductClassExpanderMock(): ProductClassExpanderInterface
    {
        return $this->getMockBuilder(ProductClassExpanderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
