<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManager;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;
use Spryker\Zed\ProductLabel\ProductLabelConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductAbstractRelationDeleter implements ProductAbstractRelationDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

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
     * @var \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductLabelEntityManager
     */
    private $productLabelEntityManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productRelationTouchManager
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManager $productLabelEntityManager
     * @param \Spryker\Zed\ProductLabel\ProductLabelConfig $productLabelConfig
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        ProductAbstractRelationTouchManagerInterface $productRelationTouchManager,
        ProductLabelRepositoryInterface $productLabelRepository,
        ProductLabelEntityManager $productLabelEntityManager,
        ProductLabelConfig $productLabelConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->productRelationTouchManager = $productRelationTouchManager;
        $this->productLabelRepository = $productLabelRepository;
        $this->productLabelEntityManager = $productLabelEntityManager;
        $this->productLabelConfig = $productLabelConfig;
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        $this->handleDatabaseTransaction(function () use ($idProductLabel, $idsProductAbstract, $isTouchEnabled) {
            $this->executeDeleteRelationsTransaction($idProductLabel, $idsProductAbstract, $isTouchEnabled);
        });
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function executeDeleteRelationsTransaction(int $idProductLabel, array $idsProductAbstract, bool $isTouchEnabled = true)
    {
        $productLabelDeAssignChunkSize = $this->productLabelConfig->getProductLabelDeAssignChunkSize();

        foreach (array_chunk($idsProductAbstract, $productLabelDeAssignChunkSize) as $idsProductAbstractChunk) {
            $this->deleteRelationsByChunk($idProductLabel, $idsProductAbstractChunk, $isTouchEnabled);
        }
    }

    /**
     * @param int $idProductLabel
     * @param array $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    protected function deleteRelationsByChunk(int $idProductLabel, array $productAbstractIds, bool $isTouchEnabled): void
    {
        $productAbstractRelations = $this->productLabelRepository->getProductAbstractRelationsByIdProductLabelAndProductAbstractIds(
            $idProductLabel,
            $productAbstractIds
        );

        if (!count($productAbstractRelations)) {
            return;
        }

        $productAbstractWithRelationsIds = $this->extractProductAbstractIdsFromProductAbstractRelations($productAbstractRelations);

        $this->productLabelEntityManager->deleteProductLabelProductAbstractRelationBatch(
            $idProductLabel,
            $productAbstractWithRelationsIds
        );

        if ($isTouchEnabled) {
            foreach ($productAbstractWithRelationsIds as $idProductAbstract) {
                $this->touchRelationsForAbstractProduct($idProductAbstract);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function touchRelationsForAbstractProduct(int $idProductAbstract): void
    {
        if ($this->isEmptyRelationForAbstractProduct($idProductAbstract)) {
            $this->productRelationTouchManager->touchDeletedByIdProductAbstract($idProductAbstract);

            return;
        }

        $this->productRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function isEmptyRelationForAbstractProduct($idProductAbstract)
    {
        $relationCount = $this
            ->queryContainer
            ->queryProductsLabelByIdProductAbstract($idProductAbstract)
            ->count();

        return ($relationCount === 0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[] $productAbstractRelations
     *
     * @return int[]
     */
    protected function extractProductAbstractIdsFromProductAbstractRelations(array $productAbstractRelations): array
    {
        $productAbstractIds = [];
        foreach ($productAbstractRelations as $productAbstractRelation) {
            $productAbstractIds[] = $productAbstractRelation->getFkProductAbstract();
        }

        return $productAbstractIds;
    }
}
