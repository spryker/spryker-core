<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface getRepository()
 */
class AbstractProductStorageListener extends AbstractPlugin
{
    /**
     * @var array<int>
     */
    protected static $publishedProductAbstractIds = [];

    /**
     * @var array<int>
     */
    protected static $unpublishedProductAbstractIds = [];

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function publishAbstractProducts(array $productAbstractIdTimestampMap)
    {
        // Filters IDs if it had been processed in the current process
        $productAbstractIds = array_values(array_unique(array_diff(array_keys($productAbstractIdTimestampMap), static::$publishedProductAbstractIds)));
        // Exclude IDs if they were processed in current process
        $productAbstractIdTimestampMap = array_intersect_key($productAbstractIdTimestampMap, array_flip($productAbstractIds));
        // Filters IDs if it had been processed in parallel processes
        $productAbstractIdsForUpdate = $this->getRepository()->getRelevantProductAbstractIdsToUpdate($productAbstractIdTimestampMap);

        if ($productAbstractIdsForUpdate) {
            $this->getFacade()->publishAbstractProducts($productAbstractIdsForUpdate);
        }
        static::$publishedProductAbstractIds = array_merge(static::$publishedProductAbstractIds, $productAbstractIds);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function unpublishProductAbstracts(array $productAbstractIdTimestampMap)
    {
        $productAbstractIds = array_values(array_unique(array_diff(array_keys($productAbstractIdTimestampMap), static::$unpublishedProductAbstractIds)));
        if ($productAbstractIds) {
            $this->getFacade()->unpublishProductAbstracts($productAbstractIds);
        }
        static::$unpublishedProductAbstractIds = array_merge(static::$unpublishedProductAbstractIds, $productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\HydrateEventsRequestTransfer $hydrateEventsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HydrateEventsResponseTransfer
     */
    protected function hydrateEventDataTransfer(HydrateEventsRequestTransfer $hydrateEventsRequestTransfer): HydrateEventsResponseTransfer
    {
        return $this->getFactory()->getEventBehaviorFacade()->hydrateEventDataTransfer($hydrateEventsRequestTransfer);
    }
}
