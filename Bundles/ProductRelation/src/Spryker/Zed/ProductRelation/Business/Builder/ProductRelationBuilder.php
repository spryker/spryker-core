<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Builder;

use Exception;
use Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class ProductRelationBuilder implements ProductRelationBuilderInterface
{
    use LoggerTrait;
    use InstancePoolingTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface
     */
    protected $productRelationUpdater;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @var \Spryker\Zed\ProductRelation\ProductRelationConfig
     */
    protected $productRelationConfig;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface $productRelationUpdater
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     * @param \Spryker\Zed\ProductRelation\ProductRelationConfig $productRelationConfig
     */
    public function __construct(
        ProductRelationUpdaterInterface $productRelationUpdater,
        ProductRelationRepositoryInterface $productRelationRepository,
        ProductRelationConfig $productRelationConfig
    ) {
        $this->productRelationUpdater = $productRelationUpdater;
        $this->productRelationRepository = $productRelationRepository;
        $this->productRelationConfig = $productRelationConfig;
    }

    /**
     * @return void
     */
    public function rebuildRelations(): void
    {
        $isInstancePoolingDisabledSuccessfully = $this->disableInstancePooling();
        $productRelationCount = $this->productRelationRepository->getActiveProductRelationCount();

        if (!$productRelationCount) {
            return;
        }

        $productRelationUpdateChunkSize = $this->productRelationConfig->getProductRelationUpdateChunkSize();
        $productRelationCriteriaFilterTransfer = (new ProductRelationCriteriaFilterTransfer())
            ->setLimit($productRelationUpdateChunkSize);

        for ($offset = 0; $offset <= $productRelationCount; $offset += $productRelationUpdateChunkSize) {
            $this->rebuildProductRelationBatch(
                $productRelationCriteriaFilterTransfer->setOffset($offset)
            );
        }

        if ($isInstancePoolingDisabledSuccessfully) {
            $this->enableInstancePooling();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer
     *
     * @return void
     */
    protected function rebuildProductRelationBatch(
        ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer
    ): void {
        $productRelationTransfers = $this->productRelationRepository
            ->getActiveProductRelations($productRelationCriteriaFilterTransfer);

        foreach ($productRelationTransfers as $productRelationTransfer) {
            $this->processRebuildRelations($productRelationTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    protected function processRebuildRelations(ProductRelationTransfer $productRelationTransfer): void
    {
        try {
            if (!$productRelationTransfer->getQuerySet()->getRules()->count()) {
                return;
            }

            $this->productRelationUpdater->updateProductRelation($productRelationTransfer);
        } catch (Exception $exception) {
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}
