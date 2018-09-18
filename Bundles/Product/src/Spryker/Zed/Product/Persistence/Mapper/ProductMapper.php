<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Store\Persistence\SpyStore;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productConcreteEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer->fromArray(
            $productConcreteEntity->toArray(),
            true
        );

        $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
        $productConcreteTransfer->setAbstractSku(
            $productConcreteEntity->getSpyProductAbstract()->getSku()
        );

        foreach ($productConcreteEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            $productConcreteTransfer->addLocalizedAttributes(
                $this->mapProductLocalizedAttributesEntityToTransfer($productLocalizedAttributesEntity, new LocalizedAttributesTransfer())
            );
        }

        foreach ($productConcreteEntity->getSpyProductAbstract()->getSpyProductAbstractStores() as $productAbstractStoreEntity) {
            $productConcreteTransfer->addStores(
                $this->mapStoreEntityToTransfer($productAbstractStoreEntity->getSpyStore(), new StoreTransfer())
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function mapProductLocalizedAttributesEntityToTransfer(
        SpyProductLocalizedAttributes $productLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer {
        $localizedAttributesTransfer->fromArray(
            $productLocalizedAttributesEntity->toArray(),
            true
        );

        $localizedAttributesTransfer->setLocale(
            $this->mapLocaleEntityToTransfer($productLocalizedAttributesEntity->getLocale(), new LocaleTransfer())
        );

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        return $localeTransfer->fromArray(
            $localeEntity->toArray(),
            true
        );
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray(
            $storeEntity->toArray(),
            true
        );
    }
}
