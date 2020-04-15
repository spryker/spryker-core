<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductNameBuilder implements ProductNameBuilderInterface
{
    protected const ATTRIBUTE_KEY_COLOR = 'color';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string|null
     */
    public function buildProductName(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ?string {
        $concreteLocalizedAttributesTransfer = $this->getConcreteLocalizedAttributesTransfer($productConcreteTransfer);
        $abstractLocalizedAttributesTransfer = $this->getAbstractLocalizedAttributesTransfer($productAbstractTransfer);

        $productConcreteName = $concreteLocalizedAttributesTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];
        $productConcreteAttributes = array_merge(
            $productConcreteTransfer->getAttributes(),
            $concreteLocalizedAttributesTransfer->getAttributes()
        );
        $productAbstractAttributes = array_merge(
            $productAbstractTransfer->getAttributes(),
            $abstractLocalizedAttributesTransfer->getAttributes()
        );

        foreach ($productConcreteAttributes as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        if (!isset($productConcreteAttributes[static::ATTRIBUTE_KEY_COLOR]) && isset($productAbstractAttributes[static::ATTRIBUTE_KEY_COLOR])) {
            $extendedProductConcreteNameParts[] = ucfirst($productAbstractAttributes[static::ATTRIBUTE_KEY_COLOR]);
        }

        return implode(', ', $extendedProductConcreteNameParts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function getConcreteLocalizedAttributesTransfer(
        ProductConcreteTransfer $productConcreteTransfer
    ): LocalizedAttributesTransfer {
        return $productConcreteTransfer->getLocalizedAttributes()->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function getAbstractLocalizedAttributesTransfer(
        ProductAbstractTransfer $productAbstractTransfer
    ): LocalizedAttributesTransfer {
        return $productAbstractTransfer->getLocalizedAttributes()->offsetGet(0);
    }
}
