<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductSelectorTransfer;

class ProductMapper
{
    /**
     * @param array $productArray
     * @param \Generated\Shared\Transfer\ProductSelectorTransfer $productSelectorTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSelectorTransfer
     */
    public function mapProductArrayToProductSelectorTransfer(
        array $productArray,
        ProductSelectorTransfer $productSelectorTransfer
    ): ProductSelectorTransfer {
        $productSelectorTransfer->fromArray($productArray, true);
        $productSelectorTransfer->setIdProductAbstract($productArray['spy_product_abstract.id_product_abstract'])
            ->setSku($productArray['spy_product_abstract.sku'])
            ->setName($productArray['spy_product_abstract_localized_attributes.name'])
            ->setDescription($productArray['spy_product_abstract_localized_attributes.description'])
            ->setPrice($productArray['spy_price_product.price'])
            ->setExternalUrlSmall($productArray['spy_product_image.external_url_small']);

        return $productSelectorTransfer;
    }
}
