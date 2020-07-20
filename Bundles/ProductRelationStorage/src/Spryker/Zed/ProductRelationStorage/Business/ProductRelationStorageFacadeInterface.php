<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductRelationStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all productRelation with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationStoreEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationPublishingEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationProductAbstractEvents()}
     *   instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * Specification:
     * - Finds and deletes productRelation storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationStoreEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationPublishingEvents()},
     *   {@link \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface::writeCollectionByProductRelationProductAbstractEvents()}
     *   instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);

    /**
     * Specification:
     * - Publishes product relation data by create, update and delete events from spy_product_relation table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product relation data by create and delete events from spy_product_relation_store table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product relation data by publish and unpublish events from spy_product_relation table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationPublishingEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product relation data by create and delete events from spy_product_relation_product_abstract table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductRelationProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of product relation storage transfer according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductRelationStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array;
}
