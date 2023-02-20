<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;
use Spryker\Zed\ProductLabel\ProductLabelConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductAbstractRelationDeleter implements ProductAbstractRelationDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface
     */
    protected $productRelationTouchManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface
     */
    protected $productLabelRepository;

    /**
     * @var \Spryker\Zed\ProductLabel\ProductLabelConfig
     */
    protected $productLabelConfig;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface
     */
    protected $productLabelEntityManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface
     */
    protected ProductEventTriggerInterface $productEventTrigger;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $productLabelEntityManager
     * @param \Spryker\Zed\ProductLabel\ProductLabelConfig $productLabelConfig
     * @param \Spryker\Zed\ProductLabel\Business\Label\Trigger\ProductEventTriggerInterface $productEventTrigger
     */
    public function __construct(
        ProductAbstractRelationTouchManagerInterface $productRelationTouchManager,
        ProductLabelRepositoryInterface $productLabelRepository,
        ProductLabelEntityManagerInterface $productLabelEntityManager,
        ProductLabelConfig $productLabelConfig,
        ProductEventTriggerInterface $productEventTrigger
    ) {
        $this->productRelationTouchManager = $productRelationTouchManager;
        $this->productLabelRepository = $productLabelRepository;
        $this->productLabelEntityManager = $productLabelEntityManager;
        $this->productLabelConfig = $productLabelConfig;
        $this->productEventTrigger = $productEventTrigger;
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $productAbstractIds, bool $isTouchEnabled = true)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $productAbstractIds, $isTouchEnabled) {
            $this->executeDeleteRelationsTransaction($idProductLabel, $productAbstractIds, $isTouchEnabled);
        });

        $this->productEventTrigger->triggerProductUpdateEvents($productAbstractIds);
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function executeDeleteRelationsTransaction(int $idProductLabel, array $productAbstractIds, bool $isTouchEnabled = true)
    {
        $productLabelToDeAssignChunkSize = $this->productLabelConfig->getProductLabelToDeAssignChunkSize();
        $productAbstractIdsChunkCollection = array_chunk($productAbstractIds, $productLabelToDeAssignChunkSize);

        foreach ($productAbstractIdsChunkCollection as $productAbstractIdsChunk) {
            $this->deleteRelationsByChunk($idProductLabel, $productAbstractIdsChunk, $isTouchEnabled);
        }
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function deleteRelationsByChunk(int $idProductLabel, array $productAbstractIds, bool $isTouchEnabled): void
    {
        $productLabelProductAbstractTransfers = $this->productLabelRepository->getProductAbstractRelationsByIdProductLabelAndProductAbstractIds(
            $idProductLabel,
            $productAbstractIds,
        );

        if (!count($productLabelProductAbstractTransfers)) {
            return;
        }

        $productAbstractIds = $this->extractProductAbstractIds($productLabelProductAbstractTransfers);

        $this->productLabelEntityManager->deleteProductLabelProductAbstractRelations(
            $idProductLabel,
            $productAbstractIds,
        );

        if (!$isTouchEnabled) {
            return;
        }

        foreach ($productAbstractIds as $idProductAbstract) {
            $this->touchRelationsForAbstractProduct($idProductAbstract);
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationsForAbstractProduct(int $idProductAbstract): void
    {
        if (!$this->productLabelRepository->checkProductLabelProductAbstractByIdProductAbstractExists($idProductAbstract)) {
            $this->productRelationTouchManager->touchDeletedByIdProductAbstract($idProductAbstract);

            return;
        }

        $this->productRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer> $productLabelProductAbstractTransfers
     *
     * @return array<int>
     */
    protected function extractProductAbstractIds(array $productLabelProductAbstractTransfers): array
    {
        $productAbstractIds = [];
        foreach ($productLabelProductAbstractTransfers as $productLabelProductAbstractTransfer) {
            $productAbstractIds[] = $productLabelProductAbstractTransfer->getFkProductAbstract();
        }

        return $productAbstractIds;
    }
}
