<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

class ProductAbstractLocalizedAttributesMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[] $productAbstractLocalizedAttributesEntities
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[] $productAbstractLocalizedAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer[]
     */
    public function mapProductAbstractLocalizedAttributesEntitiesToProductAbstractLocalizedAttributesTransfers(
        ObjectCollection $productAbstractLocalizedAttributesEntities,
        array $productAbstractLocalizedAttributesTransfers
    ): array {
        foreach ($productAbstractLocalizedAttributesEntities as $productAbstractLocalizedAttributesEntity) {
            $productAbstractLocalizedAttributesTransfer = $this->mapProductAbstractLocalizedAttributesEntityToTransfer(
                $productAbstractLocalizedAttributesEntity,
                new ProductAbstractLocalizedAttributesTransfer()
            );

            $localeTransfer = $this->mapLocaleEntityToLocaleTransfer(
                $productAbstractLocalizedAttributesEntity->getLocale(),
                new LocaleTransfer()
            );

            $productAbstractLocalizedAttributesTransfer->setLocale($localeTransfer);
            $productAbstractLocalizedAttributesTransfers[] = $productAbstractLocalizedAttributesTransfer;
        }

        return $productAbstractLocalizedAttributesTransfers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer
     */
    protected function mapProductAbstractLocalizedAttributesEntityToTransfer(
        SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity,
        ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
    ): ProductAbstractLocalizedAttributesTransfer {
        return $productAbstractLocalizedAttributesTransfer
            ->fromArray($productAbstractLocalizedAttributesEntity->toArray(), true)
            ->setIdProductAbstract($productAbstractLocalizedAttributesEntity->getFkProductAbstract());
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToLocaleTransfer(
        SpyLocale $localeEntity,
        LocaleTransfer $localeTransfer
    ): LocaleTransfer {
        return $localeTransfer->fromArray($localeEntity->toArray(), true);
    }
}
