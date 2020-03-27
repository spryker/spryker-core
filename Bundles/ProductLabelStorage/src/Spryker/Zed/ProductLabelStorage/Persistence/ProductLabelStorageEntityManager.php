<?php


namespace Spryker\Zed\ProductLabelStorage\Persistence;


/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageEntityManager implements ProductLabelStorageEntityManagerInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractLabelStoragesByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->delete();
    }
}
