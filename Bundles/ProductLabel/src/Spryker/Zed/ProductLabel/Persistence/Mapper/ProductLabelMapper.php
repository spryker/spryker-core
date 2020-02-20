<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductLabel\ProductLabelConfig;

class ProductLabelMapper
{
    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper
     */
    protected $productLabelStoreRelationMapper;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper $productLabelStoreRelationMapper
     */
    public function __construct(ProductLabelStoreRelationMapper $productLabelStoreRelationMapper)
    {
        $this->productLabelStoreRelationMapper = $productLabelStoreRelationMapper;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        $productLabelTransfer->setValidFrom(
            $productLabelEntity->getValidFrom(ProductLabelConfig::VALIDITY_DATE_FORMAT)
        );
        $productLabelTransfer->setValidTo(
            $productLabelEntity->getValidTo(ProductLabelConfig::VALIDITY_DATE_FORMAT)
        );

        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity($productLabelEntity->getIdProductLabel());
        $productLabelTransfer->setStoreRelation(
            $this->productLabelStoreRelationMapper->mapProductLabelStoreEntitiesToStoreRelationTransfer(
                $productLabelEntity->getProductLabelStores(),
                $storeRelationTransfer
            )
        );

        return $productLabelTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabel[] $productLabelEntities
     * @param array $transferCollection
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function mapProductLabelEntitiesToProductLabelTransfers(
        ObjectCollection $productLabelEntities,
        array $transferCollection = []
    ): array {
        foreach ($productLabelEntities as $productLabelEntity) {
            $transferCollection[] = $this->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer());
        }

        return $transferCollection;
    }
}
