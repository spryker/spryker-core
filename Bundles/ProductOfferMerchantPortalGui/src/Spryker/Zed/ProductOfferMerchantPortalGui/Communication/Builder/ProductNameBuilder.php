<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductNameBuilder implements ProductNameBuilderInterface
{
    protected const ATTRIBUTE_KEY_COLOR = 'color';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    public function buildProductName(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $concreteLocalizedAttributesTransfer = $this->getConcreteLocalizedAttributesTransfer($productConcreteTransfer);

        $productConcreteName = $concreteLocalizedAttributesTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];
        $productConcreteAttributes = array_merge(
            $productConcreteTransfer->getAttributes(),
            $concreteLocalizedAttributesTransfer->getAttributes()
        );

        foreach ($productConcreteAttributes as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
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
}
