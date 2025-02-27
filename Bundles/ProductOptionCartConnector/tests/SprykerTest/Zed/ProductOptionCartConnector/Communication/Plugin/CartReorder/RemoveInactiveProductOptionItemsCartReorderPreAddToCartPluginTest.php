<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\ProductOptionCartConnector\Communication\Plugin\CartReorder\RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToMessengerFacadeInterface;
use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use SprykerTest\Zed\ProductOptionCartConnector\ProductOptionCartConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionCartConnector
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group RemoveInactiveProductOptionItemsCartReorderPreAddToCartPluginTest
 * Add your own group annotations below this line
 */
class RemoveInactiveProductOptionItemsCartReorderPreAddToCartPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductOptionCartConnector\Business\Messenger\ProductOptionMessenger::INFO_MESSAGE_INACTIVE_PRODUCT_OPTION_ITEM_REMOVED
     *
     * @var string
     */
    protected const INFO_MESSAGE_INACTIVE_PRODUCT_OPTION_ITEM_REMOVED = 'cart_reorder.pre_add_to_cart.inactive_product_option_item_removed';

    /**
     * @uses \Spryker\Zed\ProductOptionCartConnector\Business\Messenger\ProductOptionMessenger::MESSAGE_PARAM_SKU
     *
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var \SprykerTest\Zed\ProductOptionCartConnector\ProductOptionCartConnectorCommunicationTester
     */
    protected ProductOptionCartConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdProductOptionValueIsNotSet(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())->addProductOption(new ProductOptionTransfer()),
        );

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idProductOptionValue" of transfer `Generated\Shared\Transfer\ProductOptionTransfer` is null.');

        // Act
        (new RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin())->preAddToCart($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenFilteredOutItemSkuIsNotSet(): void
    {
        // Arrange
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues([
            ProductOptionGroupTransfer::ACTIVE => false,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())->addItem(
            (new ItemTransfer())
                ->addProductOption((new ProductOptionTransfer())->setIdProductOptionValue(
                    $productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue(),
                )),
        );

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "sku" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.');

        // Act
        (new RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin())->preAddToCart($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testFiltersOutItemWithInactiveProductOption(): void
    {
        // Arrange
        $productOptionGroupTransfer1 = $this->tester->haveProductOptionGroupWithValues([
            ProductOptionGroupTransfer::ACTIVE => false,
        ]);
        $productOptionGroupTransfer2 = $this->tester->haveProductOptionGroupWithValues([
            ProductOptionGroupTransfer::ACTIVE => true,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setSku('sku1'))
            ->addItem(
                (new ItemTransfer())
                    ->setSku('sku2')
                    ->addProductOption((new ProductOptionTransfer())->setIdProductOptionValue(
                        $productOptionGroupTransfer1->getProductOptionValues()[0]->getIdProductOptionValue(),
                    )),
            )->addItem(
                (new ItemTransfer())
                    ->setSku('sku3')
                    ->addProductOption((new ProductOptionTransfer())->setIdProductOptionValue(
                        $productOptionGroupTransfer2->getProductOptionValues()[0]->getIdProductOptionValue(),
                    )),
            );

        // Act
        $filteredCartChangeTransfer = (new RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin())->preAddToCart($cartChangeTransfer);

        // Assert
        $this->assertCount(2, $filteredCartChangeTransfer->getItems());
        $this->assertSame('sku1', $filteredCartChangeTransfer->getItems()->offsetGet(0)->getSku());
        $this->assertSame('sku3', $filteredCartChangeTransfer->getItems()->offsetGet(1)->getSku());
    }

    /**
     * @return void
     */
    public function testDoesNotFilterOutItemsWithoutProductOptions(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setSku('sku1'))
            ->addItem((new ItemTransfer())->setSku('sku2'));

        // Act
        $filteredCartChangeTransfer = (new RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin())->preAddToCart($cartChangeTransfer);

        // Assert
        $this->assertCount(2, $filteredCartChangeTransfer->getItems());
        $this->assertSame('sku1', $filteredCartChangeTransfer->getItems()->offsetGet(0)->getSku());
        $this->assertSame('sku2', $filteredCartChangeTransfer->getItems()->offsetGet(1)->getSku());
    }

    /**
     * @return void
     */
    public function testAddsInfoMessageForFilteredOutItems(): void
    {
        // Arrange
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues([
            ProductOptionGroupTransfer::ACTIVE => false,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setSku('sku1'))
            ->addItem(
                (new ItemTransfer())
                    ->setSku('sku2')
                    ->addProductOption((new ProductOptionTransfer())->setIdProductOptionValue(
                        $productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue(),
                    )),
            );

        // Assert
        $this->mockMessengerFacadeDependency(
            (new MessageTransfer())
                ->setValue(static::INFO_MESSAGE_INACTIVE_PRODUCT_OPTION_ITEM_REMOVED)
                ->setParameters([static::MESSAGE_PARAM_SKU => 'sku2']),
        );

        // Act
        (new RemoveInactiveProductOptionItemsCartReorderPreAddToCartPlugin())->preAddToCart($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    protected function mockMessengerFacadeDependency(MessageTransfer $messageTransfer): void
    {
        $messengerFacadeMock = $this->createMock(ProductOptionCartConnectorToMessengerFacadeInterface::class);
        $messengerFacadeMock->expects($this->once())
            ->method('addInfoMessage')
            ->with($messageTransfer);

        $this->tester->setDependency(
            ProductOptionCartConnectorDependencyProvider::FACADE_MESSENGER,
            $messengerFacadeMock,
        );
    }
}
