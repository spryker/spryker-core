<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Propel\Runtime\Collection\Collection;

class ProductManagementAttributeMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $productManagementAttributeEntities
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function mapProductManagementAttributeEntityCollectionToTransferCollection(
        Collection $productManagementAttributeEntities,
        ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
    ): ProductManagementAttributeCollectionTransfer {
        foreach ($productManagementAttributeEntities as $productManagementAttributeEntity) {
            $productManagementAttributeTransfer = $this->mapProductManagementAttributeEntityToTransfer(
                $productManagementAttributeEntity,
                new ProductManagementAttributeTransfer()
            );

            $productManagementAttributeCollectionTransfer->addProductManagementAttribute($productManagementAttributeTransfer);
        }

        return $productManagementAttributeCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productManagementAttributeValueEntities
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[] $productManagementAttributeValueTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function mapProductManagementAttributeValueEntityCollectionToTransferCollection(
        Collection $productManagementAttributeValueEntities,
        array $productManagementAttributeValueTransfers = []
    ): array {
        foreach ($productManagementAttributeValueEntities as $productManagementAttributeValueEntity) {
            $productManagementAttributeValueTransfer = (new ProductManagementAttributeValueTransfer())
                ->fromArray($productManagementAttributeValueEntity->toArray(), true);

            foreach ($productManagementAttributeValueEntity->getSpyProductManagementAttributeValueTranslations() as $productManagementAttributeValueTranslationEntity) {
                $productManagementAttributeValueTranslationTransfer = (new ProductManagementAttributeValueTranslationTransfer())
                    ->fromArray($productManagementAttributeValueTranslationEntity->toArray(), true)
                    ->setLocaleName($productManagementAttributeValueTranslationEntity->getSpyLocale()->getLocaleName());

                $productManagementAttributeValueTransfer->addLocalizedValue($productManagementAttributeValueTranslationTransfer);
            }

            $productManagementAttributeValueTransfers[] = $productManagementAttributeValueTransfer;
        }

        return $productManagementAttributeValueTransfers;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productManagementAttributeEntity
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function mapProductManagementAttributeEntityToTransfer(
        SpyProductManagementAttribute $productManagementAttributeEntity,
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        $productManagementAttributeTransfer
            ->fromArray($productManagementAttributeEntity->toArray(), true)
            ->setKey($productManagementAttributeEntity->getSpyProductAttributeKey()->getKey())
            ->setIsSuper($productManagementAttributeEntity->getSpyProductAttributeKey()->getIsSuper());

        return $productManagementAttributeTransfer;
    }
}
