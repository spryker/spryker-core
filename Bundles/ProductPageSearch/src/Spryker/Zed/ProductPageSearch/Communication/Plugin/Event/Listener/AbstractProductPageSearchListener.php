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
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
abstract class AbstractProductPageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @var array<int>
     */
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
        $this->getBusinessFactory()->createProductAbstractPagePublisher()->publishWithTimestamps($productAbstractIdTimestampMap);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIdTimestampMap)
    {
        $this->getBusinessFactory()->createProductAbstractPagePublisher()->unpublishWithTimestamps($productAbstractIdTimestampMap);
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
