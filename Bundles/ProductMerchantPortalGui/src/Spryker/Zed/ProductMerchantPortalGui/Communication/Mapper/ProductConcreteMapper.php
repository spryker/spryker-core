<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductConcreteMapper implements ProductConcreteMapperInterface
{
    protected const FIELD_NAME = 'name';
    protected const FIELD_SKU = 'sku';
    protected const FIELD_ATTRIBUTE = 'attribute';
    protected const FIELD_SUPER_ATTRIBUTES = 'superAttributes';
    protected const FIELD_VALUE = 'value';

    /**
     * @param mixed[] $concreteProducts
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapRequestDataToProductConcreteTransfers(
        array $concreteProducts,
        array $productConcreteTransfers
    ): array {
        foreach ($concreteProducts as $concreteProductData) {
            $attributes = $this->reformatSuperAttributes($concreteProductData);

            $concreteProductTransfer = (new ProductConcreteTransfer())
                ->setSku($concreteProductData[static::FIELD_SKU])
                ->setName($concreteProductData[static::FIELD_NAME])
                ->setIsActive(false)
                ->setAttributes($attributes);

            $productConcreteTransfers[] = $concreteProductTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param mixed[] $concreteProductData
     *
     * @return string[]
     */
    protected function reformatSuperAttributes(array $concreteProductData): array
    {
        $attributes = [];

        if (!isset($concreteProductData[static::FIELD_SUPER_ATTRIBUTES])) {
            return $attributes;
        }

        foreach ($concreteProductData[static::FIELD_SUPER_ATTRIBUTES] as $superAttribute) {
            $attributeKey = $superAttribute[static::FIELD_VALUE];
            $attributeValue = $superAttribute[static::FIELD_ATTRIBUTE][static::FIELD_VALUE];
            $attributes[$attributeKey] = $attributeValue;
        }

        return $attributes;
    }
}
