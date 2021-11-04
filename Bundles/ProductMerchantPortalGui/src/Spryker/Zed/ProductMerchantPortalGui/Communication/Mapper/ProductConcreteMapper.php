<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductConcreteMapper implements ProductConcreteMapperInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @var string
     */
    protected const FIELD_ATTRIBUTE = 'attribute';

    /**
     * @var string
     */
    protected const FIELD_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @var string
     */
    protected const FIELD_VALUE = 'value';

    /**
     * @param array<mixed> $productConcreteData
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapProductConcreteDataToProductConcreteTransfers(
        array $productConcreteData,
        array $productConcreteTransfers
    ): array {
        foreach ($productConcreteData as $productConcrete) {
            $attributes = $this->reformatSuperAttributes($productConcrete);

            $concreteProductTransfer = (new ProductConcreteTransfer())
                ->setSku($productConcrete[static::FIELD_SKU])
                ->setName($productConcrete[static::FIELD_NAME])
                ->setIsActive(false)
                ->setAttributes($attributes);

            $productConcreteTransfers[] = $concreteProductTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<mixed> $productConcrete
     *
     * @return array<string>
     */
    protected function reformatSuperAttributes(array $productConcrete): array
    {
        $attributes = [];

        if (!isset($productConcrete[static::FIELD_SUPER_ATTRIBUTES])) {
            return $attributes;
        }

        foreach ($productConcrete[static::FIELD_SUPER_ATTRIBUTES] as $superAttribute) {
            $attributeKey = $superAttribute[static::FIELD_VALUE];
            $attributeValue = $superAttribute[static::FIELD_ATTRIBUTE][static::FIELD_VALUE];
            $attributes[$attributeKey] = $attributeValue;
        }

        return $attributes;
    }
}
