<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductsRestApi\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientBridge;
use Spryker\Glue\MerchantProductsRestApi\MerchantProductsRestApiFactory;
use Spryker\Glue\MerchantProductsRestApi\Plugin\CartsRestApi\MerchantProductCartItemExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductsRestApi
 * @group Communication
 * @group Plugin
 * @group MerchantProductCartItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductCartItemExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\MerchantProductsRestApi\Processor\Expander\MerchantProductCartItemExpander::ID_PRODUCT_ABSTRACT
     */
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @uses \Spryker\Glue\MerchantProductsRestApi\Processor\Expander\MerchantProductCartItemExpander::MERCHANT_REFERENCE
     */
    protected const MERCHANT_REFERENCE = 'merchant_reference';

    protected const VALID_SKU = 'valid-sku';
    protected const VALID_MERCHANT_REFERENCE = 'valid-merchant-reference';
    protected const INVALID_MERCHANT_REFERENCE = 'invalid-merchant-reference';

    /**
     * @var \SprykerTest\Zed\MerchantProductsRestApi\MerchantProductsRestApiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPopulatesRequestWithValidMerchantReference(): void
    {
        // Arrange
        $merchantProductCartItemExpanderPlugin = $this->createMerchantProductCartItemExpanderPlugin();

        // Act
        $cartItemRequestTransfer = $merchantProductCartItemExpanderPlugin->expand(
            new CartItemRequestTransfer(),
            (new RestCartItemsAttributesTransfer())
                ->setSku(static::VALID_SKU)
                ->setMerchantReference(static::VALID_MERCHANT_REFERENCE)
        );

        // Assert
        $this->assertSame($cartItemRequestTransfer->getMerchantReference(), static::VALID_MERCHANT_REFERENCE);
    }

    /**
     * @return void
     */
    public function testExpandIgnoresInvalidMerchantReference(): void
    {
        // Arrange
        $merchantProductCartItemExpanderPlugin = $this->createMerchantProductCartItemExpanderPlugin();

        // Act
        $cartItemRequestTransfer = $merchantProductCartItemExpanderPlugin->expand(
            new CartItemRequestTransfer(),
            (new RestCartItemsAttributesTransfer())
                ->setSku(static::VALID_SKU)
                ->setMerchantReference(static::INVALID_MERCHANT_REFERENCE)
        );

        // Assert
        $this->assertSame($cartItemRequestTransfer->getMerchantReference(), null);
    }

    /**
     * @return \Spryker\Glue\MerchantProductsRestApi\Plugin\CartsRestApi\MerchantProductCartItemExpanderPlugin
     */
    protected function createMerchantProductCartItemExpanderPlugin(): MerchantProductCartItemExpanderPlugin
    {
        $productStorageClientMock = $this->createMock(MerchantProductsRestApiToProductStorageClientBridge::class);
        $productStorageClientMock->method('findProductConcreteStorageDataByMapping')->willReturn([
            static::ID_PRODUCT_ABSTRACT => 1,
        ]);

        $productStorageClientMock->method('findProductAbstractStorageData')->willReturn([
            static::MERCHANT_REFERENCE => static::VALID_MERCHANT_REFERENCE,
        ]);

        $localeClientMock = $this->createMock(MerchantProductsRestApiToLocaleClientInterface::class);
        $localeClientMock->method('getCurrentLocale')->willReturn('DE');

        $merchantProductRestApiFactoryMock = $this->createPartialMock(MerchantProductsRestApiFactory::class, ['getProductStorageClient', 'getLocaleClient']);
        $merchantProductRestApiFactoryMock->method('getProductStorageClient')
            ->willReturn($productStorageClientMock);

        $merchantProductRestApiFactoryMock->method('getLocaleClient')
            ->willReturn($localeClientMock);

        return (new MerchantProductCartItemExpanderPlugin())
            ->setFactory($merchantProductRestApiFactoryMock);
    }
}
