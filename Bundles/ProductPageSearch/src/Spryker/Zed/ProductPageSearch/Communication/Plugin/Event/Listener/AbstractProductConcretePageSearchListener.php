<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Generated\Shared\Transfer\HydrateEventsResponseTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
abstract class AbstractProductConcretePageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @var array<int>
     */
    protected static $publishedProductConcreteIds = [];

    /**
     * @var array<int>
     */
    protected static $unpublishedProductConcreteIds = [];

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    protected function publish(array $productIdTimestampMap): void
    {
        // Filters IDs if it had been processed in the current process
        $productIds = array_values(array_unique(array_diff(array_keys($productIdTimestampMap), static::$publishedProductConcreteIds)));
        // Exclude IDs if they were processed in current process
        $productIdTimestampMap = array_intersect_key($productIdTimestampMap, array_flip($productIds));
        // Filters IDs if it had been processed in parallel processes
        $productIdsForUpdate = $this->getRepository()->getRelevantProductConcreteIdsToUpdate($productIdTimestampMap);

        if ($productIdsForUpdate) {
            $this->getFacade()->publishProductConcretes($productIdsForUpdate);
        }
        static::$publishedProductConcreteIds = array_merge(static::$publishedProductConcreteIds, $productIds);
    }

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return void
     */
    protected function unpublish(array $productIdTimestampMap): void
    {
        $productIds = array_values(array_unique(array_diff(array_keys($productIdTimestampMap), static::$unpublishedProductConcreteIds)));
        if ($productIds) {
            $this->getFacade()->unpublishProductConcretes($productIds);
        }

        static::$unpublishedProductConcreteIds = array_merge(static::$unpublishedProductConcreteIds, $productIds);
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
