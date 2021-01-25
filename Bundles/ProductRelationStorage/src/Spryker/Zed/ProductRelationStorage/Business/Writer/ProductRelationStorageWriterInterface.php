<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Writer;

interface ProductRelationStorageWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationPublishingEvents(
        array $eventTransfers
    ): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationStoreEvents(
        array $eventTransfers
    ): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationEvents(array $eventTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductRelationStorageCollectionByProductRelationProductAbstractEvents(
        array $eventTransfers
    ): void;

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationPublishingEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationStoreEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter::writeProductRelationStorageCollectionByProductRelationProductAbstractEvents()}
     *   instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);
}
