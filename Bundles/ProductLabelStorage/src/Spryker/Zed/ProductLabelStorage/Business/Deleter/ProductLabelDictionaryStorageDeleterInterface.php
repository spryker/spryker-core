<?php


namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;


interface ProductLabelDictionaryStorageDeleterInterface
{
    /**
     * @deprecated
     *
     * @return void
     */
    public function unpublish();

    /**
     * @param array $eventTransfers
     * @return void
     */
    public function deleteProductLabelDictionaryStorageCollectionByProductLabelEvents(array $eventTransfers): void;
}
