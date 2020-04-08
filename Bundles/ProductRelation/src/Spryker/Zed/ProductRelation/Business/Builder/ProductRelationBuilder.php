<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Builder;

use Exception;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class ProductRelationBuilder implements ProductRelationBuilderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface
     */
    protected $productRelationUpdater;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface $productRelationUpdater
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     */
    public function __construct(
        ProductRelationUpdaterInterface $productRelationUpdater,
        ProductRelationRepositoryInterface $productRelationRepository
    ) {
        $this->productRelationUpdater = $productRelationUpdater;
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * @return void
     */
    public function rebuildRelations(): void
    {
        $activeProductRelations = $this->productRelationRepository->getActiveProductRelations();
        foreach ($activeProductRelations as $productRelationTransfer) {
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
