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
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
abstract class AbstractProductPageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
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
     * @param array<int, int|null> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function publish(array $productAbstractIdTimestampMap)
    {
        // Filters IDs if it had been processed in the current process
        $productAbstractIds = array_values(array_unique(array_diff(array_keys($productAbstractIdTimestampMap), static::$publishedProductAbstractIds)));
        // Exclude IDs if they were processed in current process
        $productAbstractIdTimestampMap = array_intersect_key($productAbstractIdTimestampMap, array_flip($productAbstractIds));
        // Filters IDs if it had been processed in parallel processes
        $productAbstractIdsForUpdate = $this->getRepository()->getRelevantProductAbstractIdsToUpdate($productAbstractIdTimestampMap);

        if ($productAbstractIdsForUpdate) {
            $this->getFacade()->publish($productAbstractIdsForUpdate);
        }
        static::$publishedProductAbstractIds = array_merge(static::$publishedProductAbstractIds, $productAbstractIds);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIdTimestampMap)
    {
        $productAbstractIds = array_values(array_unique(array_diff(array_keys($productAbstractIdTimestampMap), static::$unpublishedProductAbstractIds)));
        if ($productAbstractIds) {
            $this->getFacade()->unpublish($productAbstractIds);
        }
        static::$unpublishedProductAbstractIds = array_merge(static::$unpublishedProductAbstractIds, $productAbstractIds);
    }

    /**
     * @param list<int> $categoryIds
     *
     * @return list<int>
     */
    protected function getRelatedCategoryIds(array $categoryIds): array
    {
        $categoryNodeTransfers = [];

        foreach ($categoryIds as $idCategory) {
            $categoryNodeTransfers = array_merge(
                $categoryNodeTransfers,
                $this->getFactory()->getCategoryFacade()->getAllNodesByIdCategory($idCategory),
            );
        }

        $categoryNodeIds = $this->extractCategoryNodeIdsFromCategoryNodes($categoryNodeTransfers);

        return array_unique($this->getRepository()->getCategoryIdsByCategoryNodeIds($categoryNodeIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIdsFromCategoryNodes(array $categoryNodeTransfers): array
    {
        $categoryNodeIds = [];

        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            $categoryNodeIds[] = $categoryNodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
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
