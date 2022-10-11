<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductConcreteMerger implements ProductConcreteMergerInterface
{
    /**
     * @var array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface>
     */
    protected array $productMergerPlugins;

    /**
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface> $productMergerPlugins
     */
    public function __construct(array $productMergerPlugins)
    {
        $this->productMergerPlugins = $productMergerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mergeProductConcreteWithProductAbstract(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        if ($productConcreteTransfer->getStores()->count() === 0 && $productAbstractTransfer->getStoreRelation()) {
            $productConcreteTransfer->setStores($productAbstractTransfer->getStoreRelation()->getStores());
        }

        $productConcreteTransfer->setAttributes(
            $this->getMergedAttributes($productConcreteTransfer, $productAbstractTransfer),
        );

        $this->mergeLocalizedAttributes($productConcreteTransfer, $productAbstractTransfer);

        return $this->executeMergerPlugins($productConcreteTransfer, $productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    protected function getMergedAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): array {
        return array_unique(
            array_merge(
                $productAbstractTransfer->getAttributes(),
                $productConcreteTransfer->getAttributes(),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function mergeLocalizedAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): void {
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $productConcreteLocalizedAttributesTransfer = $this->getProductConcreteLocalizedAttributesByLocale(
                $productConcreteTransfer,
                $localizedAttributesTransfer,
            );

            if ($productConcreteLocalizedAttributesTransfer !== null) {
                $this->mergeLocalizedAttributesData($productConcreteLocalizedAttributesTransfer, $localizedAttributesTransfer);
            } else {
                $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    protected function getProductConcreteLocalizedAttributesByLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): ?LocalizedAttributesTransfer {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $productConcreteLocalizedAttributeTransfer) {
            if (
                $productConcreteLocalizedAttributeTransfer->getLocale()->getIdLocale()
                === $localizedAttributesTransfer->getLocale()->getIdLocale()
            ) {
                return $productConcreteLocalizedAttributeTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $productConcreteLocalizedAttributesTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
     *
     * @return void
     */
    protected function mergeLocalizedAttributesData(
        LocalizedAttributesTransfer $productConcreteLocalizedAttributesTransfer,
        LocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
    ): void {
        if (!$productConcreteLocalizedAttributesTransfer->getName()) {
            $productConcreteLocalizedAttributesTransfer->setName($productAbstractLocalizedAttributesTransfer->getName());
        }

        if (!$productConcreteLocalizedAttributesTransfer->getDescription()) {
            $productConcreteLocalizedAttributesTransfer->setDescription($productAbstractLocalizedAttributesTransfer->getDescription());
        }

        if (!$productConcreteLocalizedAttributesTransfer->getMetaTitle()) {
            $productConcreteLocalizedAttributesTransfer->setMetaTitle($productAbstractLocalizedAttributesTransfer->getMetaTitle());
        }

        if (!$productConcreteLocalizedAttributesTransfer->getMetaDescription()) {
            $productConcreteLocalizedAttributesTransfer->setMetaDescription($productAbstractLocalizedAttributesTransfer->getMetaDescription());
        }

        if (!$productConcreteLocalizedAttributesTransfer->getMetaKeywords()) {
            $productConcreteLocalizedAttributesTransfer->setMetaKeywords($productAbstractLocalizedAttributesTransfer->getMetaKeywords());
        }

        $productConcreteLocalizedAttributesTransfer->setAttributes(
            array_unique(
                array_merge(
                    $productAbstractLocalizedAttributesTransfer->getAttributes(),
                    $productConcreteLocalizedAttributesTransfer->getAttributes(),
                ),
            ),
        );

        if ($productConcreteLocalizedAttributesTransfer->getIsSearchable() === null) {
            $productConcreteLocalizedAttributesTransfer->setIsSearchable($productAbstractLocalizedAttributesTransfer->getIsSearchable());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executeMergerPlugins(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        foreach ($this->productMergerPlugins as $productMergerPlugin) {
            $productConcreteTransfer = $productMergerPlugin->merge($productConcreteTransfer, $productAbstractTransfer);
        }

        return $productConcreteTransfer;
    }
}
