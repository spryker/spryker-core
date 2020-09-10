<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory getFactory()
 */
class ProductConfigurationStorageClient extends AbstractClient implements ProductConfigurationStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceReader()
            ->findProductConfigurationInstanceBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $groupKey
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceInQuote(
        string $groupKey,
        string $sku,
        QuoteTransfer $quoteTransfer
    ): ?ProductConfigurationInstanceTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceQuoteReader()
            ->findProductConfigurationInstanceInQuote($groupKey, $sku, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void {
        $this->getFactory()
            ->createProductConfigurationInstanceWriter()
            ->storeProductConfigurationInstanceBySku($sku, $productConfigurationInstanceTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        return $this->getFactory()
            ->createProductViewExpander()
            ->expandProductViewWithProductConfigurationInstance(
                $productViewTransfer,
                $productData,
                $localeName,
                $productStorageCriteriaTransfer
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesByIdProductConcrete(int $idProductConcrete): array
    {
        return $this->getFactory()
            ->createProductConfigurationPriceReader()
            ->findProductConcretePricesByIdProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expandPriceProductFilterWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductFilterTransfer {
        return $this->getFactory()
            ->createPriceProductFilterExpander()
            ->expandPriceProductFilterWithProductConfigurationInstance($productViewTransfer, $priceProductFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductHasProductConfigurationInstance(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getFactory()
            ->createProductConfigurationAvailabilityReader()
            ->isProductHasProductConfigurationInstance($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductConcreteAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getFactory()
            ->createProductConfigurationAvailabilityReader()
            ->isProductConcreteAvailable($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params
    ): CartChangeTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceCartChangeExpander()
            ->expandCartChangeWithProductConfigurationInstance($cartChangeTransfer, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params
    ): PersistentCartChangeTransfer {
        return $this->getFactory()
            ->createProductConfigurationInstanceCartChangeExpander()
            ->expandPersistentCartChangeWithProductConfigurationInstance($persistentCartChangeTransfer, $params);
    }
}
