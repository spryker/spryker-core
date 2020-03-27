<?php


namespace Spryker\Zed\ProductLabelStorage\Persistence;


interface ProductLabelStorageEntityManagerInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractLabelStoragesByProductAbstractIds(array $productAbstractIds): void;
}
