<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;

class ProductAttributeMapper implements ProductAttributeMapperInterface
{
    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productManagementAttributeEntity
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function mapProductManagementAttributeEntityToTransfer(SpyProductManagementAttribute $productManagementAttributeEntity, ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $productManagementAttributeTransfer->fromArray($productManagementAttributeEntity->toArray(), true);
        $productManagementAttributeTransfer->fromArray($productManagementAttributeEntity->getSpyProductAttributeKey()->toArray(), true);

        foreach ($productManagementAttributeEntity->getSpyProductManagementAttributeValues() as $productManagementAttributeValueEntity) {
            $productManagementAttributeTransfer->addValue(
                $this->mapProductManagementAttributeValueEntityToTransfer($productManagementAttributeValueEntity, new ProductManagementAttributeValueTransfer())
            );
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue $productManagementAttributeValueEntity
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    protected function mapProductManagementAttributeValueEntityToTransfer(
        SpyProductManagementAttributeValue $productManagementAttributeValueEntity,
        ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer
    ): ProductManagementAttributeValueTransfer {
        return $productManagementAttributeValueTransfer->fromArray(
            $productManagementAttributeValueEntity->toArray()
        );
    }
}
