<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Business\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Plugin
 * @group Facade
 * @group ProductCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductCartConnectorFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_URL_EN = '/en/product-1';

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
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandItemTransfersWithUrlsForCartWithItem(): void
    {
        // Arrange
        $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => 'en_US']);

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);

        $productUrl = $this->tester->haveUrl([
            UrlTransfer::FK_LOCALE => $this->tester->getLocator()->locale()->facade()->getCurrentLocale()->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            UrlTransfer::URL => static::PRODUCT_URL_EN,
        ]);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemTransfer())->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract()));

        // Act
        $this->tester->getFacade()->expandItemTransfersWithUrls($cartChangeTransfer);

        // Assert
        $this->assertSame($productUrl->getUrl(), $cartChangeTransfer->getItems()->offsetGet(0)->getUrl());
    }

    /**
     * @return void
     */
    public function testExpandItemTransfersWithUrlsForEmptyCart(): void
    {
        // Arrange
        $cartChangeTransfer = new CartChangeTransfer();

        // Act
        $this->tester->getFacade()->expandItemTransfersWithUrls($cartChangeTransfer);

        // Assert
        $this->assertCount(0, $cartChangeTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testValidateCheckoutQuoteItemsWillReturnErrorIfProductConcreteDoesNotExist(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->build();
        $itemConcreteSku = $quoteTransfer->getItems()->offsetGet(0)->getSku();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

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
    public function testValidateCheckoutQuoteItemsWillReturnErrorIfProductConcreteIsInactive(): void
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
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

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
    public function testValidateCheckoutQuoteItemsWillReturnErrorIfProductAbstractDoesNotExist(): void
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
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

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
    public function testValidateCheckoutQuoteItemsWillNotReturnErrorIfQuoteDoesNotHaveItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->build();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCheckoutQuoteItemsWillNotReturnErrorIfProductConcreteIsValid(): void
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
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCheckoutQuoteItemsWillNotReturnErrorIfProductAbstractIsValid(): void
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
        $isValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isValid);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateCheckoutQuoteItemsWillThrowExceptionIfItemSkuAndAbstractSkuAreNotProvided(): void
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
        $this->expectErrorMessage(sprintf('Property "%s" of transfer `%s` is null.', ItemTransfer::ABSTRACT_SKU, ItemTransfer::class));

        // Act
        $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);
    }
}
