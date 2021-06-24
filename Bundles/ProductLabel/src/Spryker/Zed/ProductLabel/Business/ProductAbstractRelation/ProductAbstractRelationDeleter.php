<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

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
    private $productLabelEntityManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $productLabelEntityManager
     * @param \Spryker\Zed\ProductLabel\ProductLabelConfig $productLabelConfig
     */
    public function __construct(
        ProductAbstractRelationTouchManagerInterface $productRelationTouchManager,
        ProductLabelRepositoryInterface $productLabelRepository,
        ProductLabelEntityManagerInterface $productLabelEntityManager,
        ProductLabelConfig $productLabelConfig
    ) {
        $this->productRelationTouchManager = $productRelationTouchManager;
        $this->productLabelRepository = $productLabelRepository;
        $this->productLabelEntityManager = $productLabelEntityManager;
        $this->productLabelConfig = $productLabelConfig;
    }

    /**
     * @param int $idProductLabel
     * @param int[] $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $productAbstractIds, bool $isTouchEnabled = true)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $productAbstractIds, $isTouchEnabled) {
            $this->executeDeleteRelationsTransaction($idProductLabel, $productAbstractIds, $isTouchEnabled);
        });
    }

    /**
     * @param int $idProductLabel
     * @param int[] $productAbstractIds
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
     * @param int[] $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function deleteRelationsByChunk(int $idProductLabel, array $productAbstractIds, bool $isTouchEnabled): void
    {
        $productLabelProductAbstractTransfers = $this->productLabelRepository->getProductAbstractRelationsByIdProductLabelAndProductAbstractIds(
            $idProductLabel,
            $productAbstractIds
        );

        if (!count($productLabelProductAbstractTransfers)) {
            return;
        }

        $productAbstractIds = $this->extractProductAbstractIds($productLabelProductAbstractTransfers);

        $this->productLabelEntityManager->deleteProductLabelProductAbstractRelations(
            $idProductLabel,
            $productAbstractIds
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
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[] $productLabelProductAbstractTransfers
     *
     * @return int[]
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
