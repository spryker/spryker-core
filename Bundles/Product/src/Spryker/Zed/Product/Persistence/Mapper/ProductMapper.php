<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface;
use Spryker\Zed\Product\Persistence\ProductRepository;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Product\Persistence\Mapper\LocalizedAttributesMapper
     */
    protected $localizedAttributeMapper;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Product\Persistence\Mapper\LocalizedAttributesMapper $localizedAttributeMapper
     */
    public function __construct(
        ProductToUtilEncodingInterface $utilEncodingService,
        LocalizedAttributesMapper $localizedAttributeMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->localizedAttributeMapper = $localizedAttributeMapper;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer->fromArray(
            $productEntity->toArray(),
            true,
        );

        $productConcreteTransfer->setIdProductConcrete($productEntity->getIdProduct());
        $productConcreteTransfer->setAbstractSku(
            $productEntity->getSpyProductAbstract()->getSku(),
        );

        $attributes = $this->utilEncodingService->decodeJson($productEntity->getAttributes(), true) ?? [];
        $productConcreteTransfer->setAttributes($attributes);

        foreach ($productEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            $productConcreteTransfer->addLocalizedAttributes(
                $this->localizedAttributeMapper
                    ->mapProductLocalizedAttributesEntityToTransfer(
                        $productLocalizedAttributesEntity,
                        new LocalizedAttributesTransfer(),
                    ),
            );
        }

        foreach ($productEntity->getSpyProductAbstract()->getSpyProductAbstractStores() as $productAbstractStoreEntity) {
            $productConcreteTransfer->addStores(
                $this->mapStoreEntityToTransfer($productAbstractStoreEntity->getSpyStore(), new StoreTransfer()),
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferForSuggestion(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productAbstractTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productAbstractTransfer->setName($productAbstractEntity->getVirtualColumn(ProductRepository::KEY_FILTERED_PRODUCTS_PRODUCT_NAME));
        $productAbstractTransfer->setSku($productAbstractEntity->getSku());

        return $productAbstractTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductEntityToProductConcreteTransferWithoutStores(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer->fromArray($productEntity->toArray(), true);

        $attributes = $this->utilEncodingService->decodeJson($productEntity->getAttributes(), true);
        $productConcreteTransfer->setAttributes(is_array($attributes) ? $attributes : []);

        $productConcreteTransfer->setIdProductConcrete($productEntity->getIdProduct());

        $productAbstractEntityTransfer = $productEntity->getSpyProductAbstract();
        if ($productAbstractEntityTransfer !== null) {
            $productConcreteTransfer->setAbstractSku($productAbstractEntityTransfer->getSku());
        }

        foreach ($productEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            $productConcreteTransfer->addLocalizedAttributes(
                $this->localizedAttributeMapper->mapProductLocalizedAttributesEntityToTransfer(
                    $productLocalizedAttributesEntity,
                    new LocalizedAttributesTransfer(),
                ),
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToProductConcreteTransferWithoutRelations(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $attributes = $this->utilEncodingService->decodeJson($productEntity->getAttributes(), true);

        return $productConcreteTransfer
            ->fromArray($productEntity->toArray(), true)
            ->setAttributes(is_array($attributes) ? $attributes : [])
            ->setIdProductConcrete($productEntity->getIdProduct());
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        return $productAbstractTransfer->fromArray($productAbstractEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function mapProductConcreteTransferToProductEntity(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyProduct $productEntity
    ): SpyProduct {
        $encodedAttributes = $this->utilEncodingService->encodeJson($productConcreteTransfer->getAttributes());
        $productConcreteData = $productConcreteTransfer->toArray();
        unset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES]);

        $productEntity->fromArray($productConcreteData);
        $productEntity->setAttributes($encodedAttributes);

        return $productEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProduct> $productEntityCollection
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductEntityCollectionPrimaryKeysToProductConcreteCollectionTransfer(
        Collection $productEntityCollection,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        $productEntitiesIndexedBySku = $this->getProductEntitiesIndexedBySku($productEntityCollection);

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            if (isset($productEntitiesIndexedBySku[$productConcreteTransfer->getSku()])) {
                $productConcreteTransfer->setIdProductConcrete(
                    $productEntitiesIndexedBySku[$productConcreteTransfer->getSku()]->getPrimaryKey(),
                );
            }
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractCollection
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function mapProductAbstractEntitiesToProductAbstractCollectionTransfer(
        Collection $productAbstractCollection,
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
    ): ProductAbstractCollectionTransfer {
        foreach ($productAbstractCollection as $productAbstractEntity) {
            $productAbstractCollectionTransfer->addProductAbstract(
                $this->mapProductAbstractEntityToProductAbstractTransfer($productAbstractEntity, (new ProductAbstractTransfer())),
            );
        }

        return $productAbstractCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransfer(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        return $productAbstractTransfer->fromArray($productAbstractEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\Product\Persistence\SpyProduct> $productEntities
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductEntitiesToProductConcreteCollection(
        Collection $productEntities,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        foreach ($productEntities as $productEntity) {
            $productConcreteTransfer = $this->mapProductEntityToProductConcreteTransferWithoutStores(
                $productEntity,
                new ProductConcreteTransfer(),
            );
            $productConcreteCollectionTransfer->addProduct($productConcreteTransfer);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProduct> $productEntityCollection
     *
     * @return array<\Orm\Zed\Product\Persistence\SpyProduct>
     */
    protected function getProductEntitiesIndexedBySku(Collection $productEntityCollection): array
    {
        $productEntitiesIndexedBySku = [];
        foreach ($productEntityCollection as $productEntity) {
            $productEntitiesIndexedBySku[$productEntity->getSku()] = $productEntity;
        }

        return $productEntitiesIndexedBySku;
    }
}
