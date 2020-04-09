<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageRepository extends AbstractRepository implements ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select(SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->distinct()
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductLabelProductAbstractTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelProductMapper()
            ->mapProductLabelProductAbstractEntitiesToTransferCollection(
                $productLabelProductAbstractEntities,
                []
            );
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractLabelStorageTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractLabelStorageEntities = $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        if (!$productAbstractLabelStorageEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductAbstractLabelStorageMapper()
            ->mapProductAbstractLabelStorageEntitiesToProductAbstractLabelStorageTransfers(
                $productAbstractLabelStorageEntities,
                []
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributesTransfers(): array
    {
        $productLabelLocalizedAttributesEntities = $this->getFactory()
            ->getProductLabelLocalizedAttributesPropelQuery()
            ->joinWithSpyProductLabel()
            ->joinWithSpyLocale()
            ->find();

        if (!$productLabelLocalizedAttributesEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelLocalizedAttributesMapper()
            ->mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
                $productLabelLocalizedAttributesEntities,
                []
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    public function getProductLabelDictionaryStorageTransfers(): array
    {
        $productLabelDictionaryEntities = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->find();

        if (!$productLabelDictionaryEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageEntitiesToProductLabelDictionaryStorageTransfers(
                $productLabelDictionaryEntities,
                []
            );
    }
}
