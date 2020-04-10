<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelLocalizedAttributesMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes[] $productLabelLocalizedAttributesEntities
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $productLabelLocalizedAttributesTransfers
     *
     * @return array
     */
    public function mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
        ObjectCollection $productLabelLocalizedAttributesEntities,
        array $productLabelLocalizedAttributesTransfers
    ): array {
        foreach ($productLabelLocalizedAttributesEntities as $productLabelLocalizedAttributesEntity) {
            $productLabelLocalizedAttributesTransfers[] = $this->mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
                $productLabelLocalizedAttributesEntity,
                new ProductLabelLocalizedAttributesTransfer()
            );
        }

        return $productLabelLocalizedAttributesTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        $productLabelLocalizedAttributesTransfer->fromArray(
            $productLabelLocalizedAttributesEntity->toArray(),
            true
        );

        return $productLabelLocalizedAttributesTransfer->fromArray($productLabelLocalizedAttributesEntity->toArray(), true)
            ->setLocale($this->mapLocaleEntityToLocaleTransfer($productLabelLocalizedAttributesEntity->getSpyLocale(), new LocaleTransfer()))
            ->setProductLabel($this->mapProductLabelEntityToProductLabelTransfer($productLabelLocalizedAttributesEntity->getSpyProductLabel(), new ProductLabelTransfer()));
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        return $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToLocaleTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        return $localeTransfer->fromArray($localeEntity->toArray(), true);
    }
}
