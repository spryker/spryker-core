<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\ProductCartConnector\Communication\Plugin\Checkout\OrderAmendmentProductExistsCheckoutPreConditionPlugin;
use SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group OrderAmendmentProductExistsCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductExistsCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidator::MESSAGE_ERROR_ABSTRACT_PRODUCT_EXISTS
     *
     * @var string
     */
    protected const ERROR_MESSAGE_ABSTRACT_PRODUCT_EXISTS = 'product-cart.validation.error.abstract-product-exists';

    /**
     * @uses \Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidator::MESSAGE_ERROR_CONCRETE_PRODUCT_EXISTS
     *
     * @var string
     */
    protected const ERROR_MESSAGE_CONCRETE_PRODUCT_EXISTS = 'product-cart.validation.error.concrete-product-exists';

    /**
     * @uses \Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidator::MESSAGE_PARAM_SKU
     *
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidator::MESSAGE_ERROR_CONCRETE_PRODUCT_INACTIVE
     *
     * @var string
     */
    protected const ERROR_MESSAGE_CONCRETE_PRODUCT_INACTIVE = 'product-cart.validation.error.concrete-product-inactive';

    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester
     */
    protected ProductCartConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnErrorIfProductConcreteDoesNotExist(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->build();
        $itemConcreteSku = $quoteTransfer->getItems()->offsetGet(0)->getSku();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertSame(static::ERROR_MESSAGE_CONCRETE_PRODUCT_EXISTS, $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage());
        $this->assertSame([static::MESSAGE_PARAM_SKU => $itemConcreteSku], $checkoutResponseTransfer->getErrors()->offsetGet(0)->getParameters());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnErrorIfProductConcreteIsInactive(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productConcreteTransfer->getSku()])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertSame(static::ERROR_MESSAGE_CONCRETE_PRODUCT_INACTIVE, $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage());
        $this->assertSame([static::MESSAGE_PARAM_SKU => $productConcreteTransfer->getSku()], $checkoutResponseTransfer->getErrors()->offsetGet(0)->getParameters());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnErrorIfProductAbstractDoesNotExist(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => null,
            ])
            ->build();
        $itemAbstractSku = $quoteTransfer->getItems()->offsetGet(0)->getAbstractSku();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertSame(static::ERROR_MESSAGE_ABSTRACT_PRODUCT_EXISTS, $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage());
        $this->assertSame([static::MESSAGE_PARAM_SKU => $itemAbstractSku], $checkoutResponseTransfer->getErrors()->offsetGet(0)->getParameters());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldNotReturnErrorIfQuoteDoesNotHaveItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldNotReturnErrorIfProductConcreteIsValid(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productConcreteTransfer->getSku()])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldNotReturnErrorIfProductAbstractIsValid(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([]);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => null,
                ItemTransfer::ABSTRACT_SKU => $productAbstractTransfer->getSku(),
            ])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldThrowExceptionIfItemSkuAndAbstractSkuAreNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => null,
                ItemTransfer::ABSTRACT_SKU => null,
            ])
            ->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "%s" of transfer `%s` is null.', ItemTransfer::ABSTRACT_SKU, ItemTransfer::class));

        // Act
        (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @return void
     */
    public function testCheckConditionShouldReturnSuccessIfProductConcreteIsInactiveAndItemsFromOriginalOrder(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productConcreteTransfer->getSku()])
            ->build();
        $quoteTransfer->addOriginalSalesOrderItem(
            (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer->getSku()),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductExistsCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }
}
