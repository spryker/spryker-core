<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface;
use Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientInterface;

class MerchantProductCartItemExpander implements MerchantProductCartItemExpanderInterface
{
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @see \Generated\Shared\Transfer\ProductAbstractStorageTransfer::ID_PRODUCT_ABSTRACT
     */
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @see \Generated\Shared\Transfer\ProductAbstractStorageTransfer::MERCHANT_REFERENCE
     */
    protected const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface $localeClient
     */
    protected $localeClient;

    /**
     * @var \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientInterface $productStorageClient
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToLocaleClientInterface $localeClient
     * @param \Spryker\Glue\MerchantProductsRestApi\Dependency\Client\MerchantProductsRestApiToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        MerchantProductsRestApiToLocaleClientInterface $localeClient,
        MerchantProductsRestApiToProductStorageClientInterface $productStorageClient
    ) {
        $this->localeClient = $localeClient;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    public function expand(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        if (!$restCartItemsAttributesTransfer->getSku()) {
            return $cartItemRequestTransfer;
        }

        /**
         * @var string $concreteSku
         */
        $concreteSku = $restCartItemsAttributesTransfer->getSku();
        $productConcreteStorageData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $concreteSku,
            $this->localeClient->getCurrentLocale()
        );

        if (!$productConcreteStorageData) {
            return $cartItemRequestTransfer;
        }

        $productAbstractStorageData = $this->productStorageClient->findProductAbstractStorageData(
            $productConcreteStorageData[static::ID_PRODUCT_ABSTRACT],
            $this->localeClient->getCurrentLocale()
        );

        if (!$productAbstractStorageData) {
            return $cartItemRequestTransfer;
        }

        if ($productAbstractStorageData[static::MERCHANT_REFERENCE] === $restCartItemsAttributesTransfer->getMerchantReference()) {
            $cartItemRequestTransfer->setMerchantReference(
                $restCartItemsAttributesTransfer->getMerchantReference()
            );
        }

        return $cartItemRequestTransfer;
    }
}
