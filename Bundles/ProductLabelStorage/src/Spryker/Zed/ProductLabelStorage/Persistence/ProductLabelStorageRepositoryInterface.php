<?php


namespace Spryker\Zed\ProductLabelStorage\Persistence;


interface ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \ArrayObject
     */
    public function getProductLabelProductAbstractTransferCollectionByProductAbstractIds(array $productAbstractIds): \ArrayObject;
}
