<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryNodePublisher implements CategoryNodePublisherInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryToEventFacadeInterface $eventFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function triggerBulkCategoryNodePublishEvent(int $idCategoryNode): void
    {
        $categoryNodeIdsToTrigger = array_merge(
            $this->getParentCategoryNodeIds($idCategoryNode),
            array_unique($this->getChildCategoryNodeIds($idCategoryNode))
        );

        $eventTransfers = [];
        foreach ($categoryNodeIdsToTrigger as $idCategoryNode) {
            $eventTransfers[] = (new EventEntityTransfer())
                ->setName(CategoryEvents::CATEGORY_NODE_PUBLISH)
                ->setId($idCategoryNode)
                ->setEvent(CategoryEvents::CATEGORY_NODE_PUBLISH);
        }

        $this->eventFacade->triggerBulk(CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE, $eventTransfers);
    }

    /**
     * @param int[]|int $parentCategoryNodeIds
     * @param int|array $categoryNodeId
     *
     * @return int[]
     */
    protected function getParentCategoryNodeIds(int $categoryNodeId, array $parentCategoryNodeIds = []): array
    {
        if (!$categoryNodeId) {
            return $parentCategoryNodeIds;
        }

        $parentCategoryNodeId = $this->categoryRepository->findParentCategoryNodeIdByCategoryNodeId($categoryNodeId);
        if (!$parentCategoryNodeId) {
            return $parentCategoryNodeIds;
        }

        $parentCategoryNodeIds[] = $parentCategoryNodeId;

        return $this->getParentCategoryNodeIds($parentCategoryNodeId, $parentCategoryNodeIds);
    }

    /**
     * @param int $categoryNodeId
     * @param int[] $childCategoryNodeIds
     *
     * @return int[]
     */
    protected function getChildCategoryNodeIds(int $categoryNodeId, array $childCategoryNodeIds = []): array
    {
        if (!$categoryNodeId) {
            return $childCategoryNodeIds;
        }

        $childCategoryNodeIdsToAdd = $this->categoryRepository->getCategoryNodeIdsByParentCategoryNodeId($categoryNodeId);
        if (!$childCategoryNodeIdsToAdd) {
            return $childCategoryNodeIds;
        }

        $childCategoryNodeIds = array_merge($childCategoryNodeIds, $childCategoryNodeIdsToAdd);
        foreach ($childCategoryNodeIdsToAdd as $childCategoryNodeIdToAdd) {
            return $this->getChildCategoryNodeIds($childCategoryNodeIdToAdd, $childCategoryNodeIds);
        }

        return $childCategoryNodeIds;
    }
}
