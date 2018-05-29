<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\Storage;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;
use Generated\Shared\Transfer\StorageProductRelationsTransfer;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToPriceProductInterface;
use Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductRelationStorage implements ProductRelationStorageInterface
{
    /**
     * @var \Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToStorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @var \Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToPriceProductInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToStorageInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     * @param \Spryker\Client\ProductRelation\Dependency\Client\ProductRelationToPriceProductInterface $priceProductClient
     */
    public function __construct(
        ProductRelationToStorageInterface $storage,
        KeyBuilderInterface $keyBuilder,
        $localeName,
        ProductRelationToPriceProductInterface $priceProductClient
    ) {

        $this->keyBuilder = $keyBuilder;
        $this->localeName = $localeName;
        $this->storage = $storage;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer[]
     */
    public function getAll($idAbstractProduct)
    {
        $key = $this->keyBuilder->generateKey($idAbstractProduct, $this->localeName);

        $productRelations = $this->storage->get($key);
        if (!$productRelations) {
            return [];
        }

        return $this->mapRelations($productRelations);
    }

    /**
     * @param array $typeRelations
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer
     */
    protected function mapStorageProductRelationsTransfer($typeRelations, $type)
    {
        $storageProductRelationsTransfer = new StorageProductRelationsTransfer();
        $storageProductRelationsTransfer->setIsActive($typeRelations[StorageProductRelationsTransfer::IS_ACTIVE]);
        $storageProductRelationsTransfer->setType($type);

        return $storageProductRelationsTransfer;
    }

    /**
     * @param array $productAbstract
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer
     */
    protected function mapStorageProductAbstractRelationTransfer(array $productAbstract)
    {
        $storageProductAbstractRelationTransfer = new StorageProductAbstractRelationTransfer();

        $storageProductAbstractRelationTransfer->fromArray($productAbstract, true);

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductAbstractPriceByPriceDimension(
            $productAbstract['prices'],
            $productAbstract['idProductAbstract']
        );

        $storageProductAbstractRelationTransfer->setPrices($currentProductPriceTransfer->getPrices());
        $storageProductAbstractRelationTransfer->setPrice($currentProductPriceTransfer->getPrice());

        return $storageProductAbstractRelationTransfer;
    }

    /**
     * @param array $typeRelations
     * @param \Generated\Shared\Transfer\StorageProductRelationsTransfer $storageProductRelationsTransfer
     *
     * @return void
     */
    protected function addAbstractProducts(
        array $typeRelations,
        StorageProductRelationsTransfer $storageProductRelationsTransfer
    ) {
        foreach ($typeRelations[StorageProductRelationsTransfer::ABSTRACT_PRODUCTS] as $productAbstract) {
            $storageProductAbstractRelationTransfer = $this->mapStorageProductAbstractRelationTransfer($productAbstract);
            $storageProductRelationsTransfer->addAbstractProduct($storageProductAbstractRelationTransfer);
        }
    }

    /**
     * @param array $productRelations
     *
     * @return array
     */
    protected function mapRelations(array $productRelations)
    {
        $relations = [];
        foreach ($productRelations as $type => $typeRelations) {
            $storageProductRelationsTransfer = $this->mapStorageProductRelationsTransfer($typeRelations, $type);
            $this->addAbstractProducts($typeRelations, $storageProductRelationsTransfer);
            $relations[$type] = $storageProductRelationsTransfer;
        }
        return $relations;
    }
}
