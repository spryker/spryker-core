<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Updater;

use Exception;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class ProductRelationBuilder implements ProductRelationBuilderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface
     */
    protected $productRelationUpdater;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Updater\ProductRelationUpdaterInterface $productRelationUpdater
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     */
    public function __construct(
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationUpdaterInterface $productRelationUpdater,
        ProductRelationRepositoryInterface $productRelationRepository
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->productRelationUpdater = $productRelationUpdater;
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * @return void
     */
    public function rebuildRelations()
    {
        foreach ($this->findActiveProductRelations() as $productRelationTransfer) {
            try {
                if (!$productRelationTransfer->getQuerySet()->getRules()) {
                    continue;
                }

                $this->productRelationUpdater->updateRelation($productRelationTransfer);
            } catch (Exception $exception) {
                $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);

                continue;
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    protected function findActiveProductRelations()
    {
        return $this->productRelationRepository->findActiveProductRelations();
    }
}
