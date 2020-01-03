<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;

class AbstractProductsResourceMapper implements AbstractProductsResourceMapperInterface
{
    protected const KEY_PRODUCT_CONCRETE_IDS = 'product_concrete_ids';
    protected const KEY_ATTRIBUTE_VARIANTS = 'attribute_variants';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const KEY_SUPER_ATTRIBUTES = 'super_attributes';

    /**
     * @param array $abstractProductData
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function mapAbstractProductsDataToAbstractProductsRestAttributes(array $abstractProductData): AbstractProductsRestAttributesTransfer
    {
        $restAbstractProductsAttributesTransfer = (new AbstractProductsRestAttributesTransfer())->fromArray($abstractProductData, true);
        $restAbstractProductsAttributesTransfer->setSuperAttributes($restAbstractProductsAttributesTransfer->getAttributeMap()[static::KEY_SUPER_ATTRIBUTES]);

        return $this->changeIdsToSkus($restAbstractProductsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $restAbstractProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    protected function changeIdsToSkus(
        AbstractProductsRestAttributesTransfer $restAbstractProductsAttributesTransfer
    ): AbstractProductsRestAttributesTransfer {
        $attributeMap = $restAbstractProductsAttributesTransfer->getAttributeMap();
        if (!isset($attributeMap[static::KEY_PRODUCT_CONCRETE_IDS])) {
            return $restAbstractProductsAttributesTransfer;
        }
        $productConcreteIds = array_flip($attributeMap[static::KEY_PRODUCT_CONCRETE_IDS]);

        if (isset($attributeMap[static::KEY_ATTRIBUTE_VARIANTS])) {
            $attributeMap[static::KEY_ATTRIBUTE_VARIANTS] = $this->changeVariantsIdsToSkus($attributeMap[static::KEY_ATTRIBUTE_VARIANTS], $productConcreteIds);
        }

        $attributeMap[static::KEY_PRODUCT_CONCRETE_IDS] = array_values($productConcreteIds);

        return $restAbstractProductsAttributesTransfer
            ->setAttributeMap($attributeMap);
    }

    /**
     * @param array $variants
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function changeVariantsIdsToSkus(array $variants, array $productConcreteIds): array
    {
        foreach ($variants as $key => $data) {
            if (isset($variants[$key][static::KEY_ID_PRODUCT_CONCRETE])) {
                $variants[$key][static::KEY_ID_PRODUCT_CONCRETE] =
                    $productConcreteIds[$data[static::KEY_ID_PRODUCT_CONCRETE]];
                continue;
            }
            $variants[$key] = $this->changeVariantsIdsToSkus($variants[$key], $productConcreteIds);
        }

        return $variants;
    }
}
