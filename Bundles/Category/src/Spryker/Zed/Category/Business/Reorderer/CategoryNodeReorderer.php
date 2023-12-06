<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Reorderer;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Filter\CategoryNodeFilterInterface;
use Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface;
use Spryker\Zed\Category\Business\Validator\CategoryNodeValidatorInterface;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryNodeReorderer implements CategoryNodeReordererInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Business\Validator\CategoryNodeValidatorInterface
     */
    protected CategoryNodeValidatorInterface $categoryNodeValidator;

    /**
     * @var \Spryker\Zed\Category\Business\Filter\CategoryNodeFilterInterface $categoryNodeFilter
     */
    protected CategoryNodeFilterInterface $categoryNodeFilter;

    /**
     * @var \Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface
     */
    protected CategoryNodeReaderInterface $categoryNodeReader;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected CategoryEntityManagerInterface $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected CategoryToEventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Business\Validator\CategoryNodeValidatorInterface $categoryNodeValidator
     * @param \Spryker\Zed\Category\Business\Filter\CategoryNodeFilterInterface $categoryNodeFilter
     * @param \Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface $categoryNodeReader
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CategoryNodeValidatorInterface $categoryNodeValidator,
        CategoryNodeFilterInterface $categoryNodeFilter,
        CategoryNodeReaderInterface $categoryNodeReader,
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryToEventFacadeInterface $eventFacade
    ) {
        $this->categoryNodeValidator = $categoryNodeValidator;
        $this->categoryNodeFilter = $categoryNodeFilter;
        $this->categoryNodeReader = $categoryNodeReader;
        $this->categoryEntityManager = $categoryEntityManager;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer
     */
    public function reorderCategoryNodeCollection(
        CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
    ): CategoryNodeCollectionResponseTransfer {
        $this->assertRequiredFields($categoryNodeCollectionRequestTransfer);

        $categoryNodeCollectionResponseTransfer = (new CategoryNodeCollectionResponseTransfer())
            ->setCategoryNodes($categoryNodeCollectionRequestTransfer->getCategoryNodes());

        $categoryNodeCollectionResponseTransfer = $this->categoryNodeValidator->validate($categoryNodeCollectionResponseTransfer);
        if ($categoryNodeCollectionRequestTransfer->getIsTransactional() && count($categoryNodeCollectionResponseTransfer->getErrors())) {
            return $categoryNodeCollectionResponseTransfer;
        }

        [$validCategoryNodeTransfers, $notValidCategoryNodeTransfers] = $this->categoryNodeFilter
            ->filterCategoryNodesByValidity($categoryNodeCollectionResponseTransfer);

        if (!$validCategoryNodeTransfers->count()) {
            return $categoryNodeCollectionResponseTransfer;
        }

        $validCategoryNodeTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validCategoryNodeTransfers) {
            return $this->executeReorderCategoryNodeCollectionTransaction($validCategoryNodeTransfers);
        });

        return $categoryNodeCollectionResponseTransfer->setCategoryNodes(
            $this->categoryNodeFilter->mergeCategoryNodes($validCategoryNodeTransfers, $notValidCategoryNodeTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer): void
    {
        $categoryNodeCollectionRequestTransfer->requireIsTransactional()
            ->requireCategoryNodes();

        foreach ($categoryNodeCollectionRequestTransfer->getCategoryNodes() as $categoryNodeTransfer) {
            $categoryNodeTransfer->requireIdCategoryNode();
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>
     */
    protected function executeReorderCategoryNodeCollectionTransaction(ArrayObject $categoryNodeTransfers): ArrayObject
    {
        $shouldTriggerCategoryTreePublishEvent = false;
        $positionCursor = $categoryNodeTransfers->count();

        $storedCategoryNodesIndexedByIdCategoryNode = $this->getStoredCategoryNodesIndexedByIdCategoryNode($categoryNodeTransfers);

        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            if ($this->executeReorderCategoryNodeTransaction($categoryNodeTransfer, $storedCategoryNodesIndexedByIdCategoryNode, $positionCursor)) {
                $shouldTriggerCategoryTreePublishEvent = true;
            }

            $positionCursor--;
        }

        if ($shouldTriggerCategoryTreePublishEvent) {
            $this->triggerCategoryTreePublishEvent();
        }

        return $categoryNodeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param array<int, \Generated\Shared\Transfer\NodeTransfer> $storedCategoryNodesIndexedByIdCategoryNode
     * @param int $nodeOrder
     *
     * @return bool
     */
    protected function executeReorderCategoryNodeTransaction(
        NodeTransfer $categoryNodeTransfer,
        array $storedCategoryNodesIndexedByIdCategoryNode,
        int $nodeOrder
    ): bool {
        $storedCategoryNodeTransfer = $storedCategoryNodesIndexedByIdCategoryNode[$categoryNodeTransfer->getIdCategoryNodeOrFail()] ?? null;
        if (!$storedCategoryNodeTransfer || $storedCategoryNodeTransfer->getNodeOrder() === $nodeOrder) {
            return false;
        }

        $this->categoryEntityManager->updateCategoryNode($storedCategoryNodeTransfer->setNodeOrder($nodeOrder));
        $categoryNodeTransfer->setNodeOrder($nodeOrder);

        return true;
    }

    /**
     * @return void
     */
    protected function triggerCategoryTreePublishEvent(): void
    {
        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_TREE_PUBLISH,
            new NodeTransfer(),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\NodeTransfer>
     */
    protected function getStoredCategoryNodesIndexedByIdCategoryNode(ArrayObject $categoryNodeTransfers): array
    {
        $categoryNodeIds = $this->extractCategoryNodeIdsFromCategoryNodeTransfers($categoryNodeTransfers);
        $categoryNodeCollectionTransfer = $this->categoryNodeReader->getCategoryNodeCollection(
            (new CategoryNodeCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds),
        );

        $categoryNodeTransfersIndexedByIdCategoryNode = [];
        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $categoryNodeTransfersIndexedByIdCategoryNode[$categoryNodeTransfer->getIdCategoryNodeOrFail()] = $categoryNodeTransfer;
        }

        return $categoryNodeTransfersIndexedByIdCategoryNode;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIdsFromCategoryNodeTransfers(ArrayObject $categoryNodeTransfers): array
    {
        $categoryNodeIds = [];
        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            $categoryNodeIds[] = $categoryNodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }
}
