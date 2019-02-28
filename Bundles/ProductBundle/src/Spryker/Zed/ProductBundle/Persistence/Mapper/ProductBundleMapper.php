<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;

class ProductBundleMapper implements ProductBundleMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $productBundleEntities
     * @param \Generated\Shared\Transfer\ProductBundleCollectionTransfer $productBundleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function mapProductBundleEntitiesToProductBundleCollectionTransfer(
        ObjectCollection $productBundleEntities,
        ProductBundleCollectionTransfer $productBundleCollectionTransfer
    ): ProductBundleCollectionTransfer {
        $productBundleEntitiesByBundleIdArray = $this->getProductBundleEntitiesByBundleIdArray($productBundleEntities);

        foreach ($productBundleEntitiesByBundleIdArray as $idProductBundle => $productBundleEntities) {
            $productBundleTransfer = new ProductBundleTransfer();
            $productBundleTransfer->setIdProductConcrete($idProductBundle);
            $productForBundleTransfer = $this->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities, new ArrayObject());
            $productBundleTransfer->setBundledProducts($productForBundleTransfer);
            $productBundleCollectionTransfer->addProductBundle($productBundleTransfer);
        }

        return $productBundleCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $productBundleEntities
     *
     * @return array
     */
    protected function getProductBundleEntitiesByBundleIdArray(ObjectCollection $productBundleEntities): array
    {
        $productBundleEntitiesByBundleIdArray = [];

        foreach ($productBundleEntities as $productBundleEntity) {
            $productBundleEntitiesByBundleIdArray[$productBundleEntity->getFkProduct()][] = $productBundleEntity;
        }

        return $productBundleEntitiesByBundleIdArray;
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[] $productBundleEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    protected function mapProductBundleEntitiesToProductForBundleTransfers(array $productBundleEntities, ArrayObject $productForBundleTransfers): ArrayObject
    {
        foreach ($productBundleEntities as $productBundleEntity) {
            $productForBundleTransfer = $this->mapProductBundleEntityToProductForBundleTransfer($productBundleEntity, new ProductForBundleTransfer());
            $productForBundleTransfers->append($productForBundleTransfer);
        }

        return $productForBundleTransfers;
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\SpyProductBundle $productBundleEntity
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer
     */
    protected function mapProductBundleEntityToProductForBundleTransfer(SpyProductBundle $productBundleEntity, ProductForBundleTransfer $productForBundleTransfer): ProductForBundleTransfer
    {
        $productForBundleTransfer->setIdProductConcrete($productBundleEntity->getFkBundledProduct());
        $sku = $productBundleEntity->getSpyProductRelatedByFkBundledProduct()->getSku();
        $productForBundleTransfer->setSku($sku);
        $productForBundleTransfer->fromArray($productBundleEntity->toArray(), true);

        return $productForBundleTransfer;
    }
}
