<?php


namespace Spryker\Zed\ProductLabelStorage\Persistence;


use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageRepository implements ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractIds = $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select(SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->getData();

        return array_unique($productAbstractIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \ArrayObject
     */
    public function getProductLabelProductAbstractTransferCollectionByProductAbstractIds(array $productAbstractIds): \ArrayObject
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->find();

        return $this->getFactory()
            ->createProductLabelProductMapper()
            ->mapProductLabelProductAbstractEntitiesToTransferCollection(
                $productLabelProductAbstractEntities,
                new \ArrayObject()
            );
    }

    public function getProductAbstractLabelStorageCollectionByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractLabelStorageEntities = $this
            ->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

//        return $this->getFactory()->
    }
}
