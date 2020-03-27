<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
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
    public function triggerBulkCategoryNodePublishEventForCreate(int $idCategoryNode): void
    {
        $categoryNodeIdsToTrigger = array_unique($this->categoryRepository->getParentCategoryNodeIdsByCategoryNodeId($idCategoryNode));

        $this->triggerBulk($categoryNodeIdsToTrigger);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function triggerBulkCategoryNodePublishEventForUpdate(int $idCategoryNode): void
    {
        $categoryNodeIdsToTrigger = array_unique(
            array_merge(
                $this->categoryRepository->getChildCategoryNodeIdsByCategoryNodeId($idCategoryNode),
                $this->categoryRepository->getParentCategoryNodeIdsByCategoryNodeId($idCategoryNode)
            )
        );

        $this->triggerBulk($categoryNodeIdsToTrigger);
    }

    /**
     * @param int[] $categoryNodeIdsToTrigger
     *
     * @return void
     */
    protected function triggerBulk(array $categoryNodeIdsToTrigger): void
    {
        $eventTransfers = [];
        foreach ($categoryNodeIdsToTrigger as $idCategoryNode) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($idCategoryNode);
        }

        $this->eventFacade->triggerBulk(CategoryEvents::CATEGORY_NODE_PUBLISH, $eventTransfers);
    }
}
