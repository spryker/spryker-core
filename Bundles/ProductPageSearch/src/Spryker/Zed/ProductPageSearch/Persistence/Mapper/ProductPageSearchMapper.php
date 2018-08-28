<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch;
use Orm\Zed\Store\Persistence\SpyStore;

class ProductPageSearchMapper implements ProductPageSearchMapperInterface
{
    public const TYPE_PRODUCT_CONCRETE = 'product_concrete';

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function mapProductConcretePageSearchEntityToTransfer(
        SpyProductConcretePageSearch $productConcretePageSearchEntity,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        return $productConcretePageSearchTransfer->fromArray(
            $productConcretePageSearchEntity->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch
     */
    public function mapProductConcretePageSearchTransferToEntity(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        SpyProductConcretePageSearch $productConcretePageSearchEntity
    ): SpyProductConcretePageSearch {
        $productConcretePageSearchEntity->fromArray(
            $productConcretePageSearchTransfer->toArray()
        );

        return $productConcretePageSearchEntity;
    }

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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function mapProductConcreteTransferToProductConcretePageSearchTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        $productConcretePageSearchTransfer->setType(static::TYPE_PRODUCT_CONCRETE);

        dump($productConcreteTransfer, $productConcretePageSearchTransfer);
        die;
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
        return $localizedAttributesTransfer->fromArray(
            $productLocalizedAttributesEntity->toArray(),
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
