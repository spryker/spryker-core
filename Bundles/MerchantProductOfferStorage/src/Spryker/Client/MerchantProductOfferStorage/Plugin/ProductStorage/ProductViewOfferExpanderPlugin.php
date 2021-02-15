<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderByCriteriaPluginInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class ProductViewOfferExpanderPlugin extends AbstractPlugin implements ProductViewExpanderByCriteriaPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReader::PARAM_SELECTED_MERCHANT_REFERENCE
     */
    protected const PARAM_SELECTED_MERCHANT_REFERENCE = 'selected_merchant_reference';

    /**
     * @uses \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReader::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE
     */
    protected const PARAM_SELECTED_MERCHANT_REFERENCE_TYPE = 'selected_merchant_reference_type';

    /**
     * {@inheritDoc}
     * - Expands the transfer object with the product offer reference according to provided criteria.
     *
     * @api
     *
     * @phpstan-param array<mixed> $productData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        if (!$productStorageCriteriaTransfer) {
            return $productViewTransfer;
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer;
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())->fromArray(
            $productStorageCriteriaTransfer->modifiedToArray(),
            true
        );
        $productOfferStorageCriteriaTransfer->fromArray($productViewTransfer->toArray(), true);

        $selectedAttributes = $productViewTransfer->getSelectedAttributes();
        $selectedProductOfferReference = isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE])
            && $selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE_TYPE] === MerchantProductOfferStorageConfig::PRODUCT_OFFER_REFERENCE_ATTRIBUTE
            && isset($selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE]) ? $selectedAttributes[static::PARAM_SELECTED_MERCHANT_REFERENCE] : null;

        $productOfferStorageCriteriaTransfer->setProductOfferReference($selectedProductOfferReference);
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($productViewTransfer->getSku());

        return $productViewTransfer->setProductOfferReference(
            $this->getClient()->findProductConcreteDefaultProductOffer($productOfferStorageCriteriaTransfer)
        );
    }
}
