<?php


namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;


interface ProductLabelStorageDeleterInterface
{
    /**
     * @deprecated
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void;

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    public function deleteProductLabelStorageCollectionByProductAbstractEvents(array $eventTransfers): void;
}
