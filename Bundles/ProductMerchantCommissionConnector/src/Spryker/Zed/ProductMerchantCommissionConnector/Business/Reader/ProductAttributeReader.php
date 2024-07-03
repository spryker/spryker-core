<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface;

class ProductAttributeReader implements ProductAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface
     */
    protected ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade;

    /**
     * @var array<string, array<string, string>>
     */
    protected static array $combinedConcreteAttributesIndexedBySku = [];

    /**
     * @param \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade
    ) {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array<string, string>
     */
    public function getCombinedConcreteAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ?LocaleTransfer $localeTransfer = null
    ): array {
        $sku = $productConcreteTransfer->getSkuOrFail();
        if (!isset(static::$combinedConcreteAttributesIndexedBySku[$sku])) {
            static::$combinedConcreteAttributesIndexedBySku[$sku] = $this->productFacade->getCombinedConcreteAttributes(
                $productConcreteTransfer,
                $localeTransfer,
            );
        }

        return static::$combinedConcreteAttributesIndexedBySku[$sku];
    }

    /**
     * @return list<string>
     */
    public function getProductAttributeKeys(): array
    {
        $productAttributeKeyCriteriaTransfer = new ProductAttributeKeyCriteriaTransfer();
        $productAttributeKeyCollectionTransfer = $this->productFacade
            ->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);

        return $this->extractProductAttributeKeys($productAttributeKeyCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractProductAttributeKeys(
        ProductAttributeKeyCollectionTransfer $productAttributeKeyCollectionTransfer
    ): array {
        $productAttributeKeys = [];
        foreach ($productAttributeKeyCollectionTransfer->getProductAttributeKeys() as $productAttributeKey) {
            $productAttributeKeys[] = $productAttributeKey->getKeyOrFail();
        }

        return $productAttributeKeys;
    }
}
