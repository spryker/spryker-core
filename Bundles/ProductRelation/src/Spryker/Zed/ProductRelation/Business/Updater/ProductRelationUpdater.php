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
use Spryker\Zed\ProductRelation\Business\Relation\ProductRelationWriterInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductRelationUpdater implements ProductRelationUpdaterInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationWriterInterface
     */
    protected $productRelationWriter;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelation\Business\Relation\ProductRelationWriterInterface $productRelationWriter
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationWriterInterface $productRelationWriter
    ) {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->productRelationWriter = $productRelationWriter;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @return void
     */
    public function rebuildRelations()
    {
        foreach ($this->findActiveProductRelations() as $productRelationEntity) {
            try {
                if (!$productRelationEntity->getQuerySetData()) {
                    continue;
                }

                $productRelationTransfer = $this->mapProductRelationTransfer($productRelationEntity);
                $this->productRelationWriter->updateRelation($productRelationTransfer);
            } catch (Exception $exception) {
                $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);
                continue;
            }
        }
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelation[]
     */
    protected function findActiveProductRelations()
    {
        return $this->productRelationQueryContainer
            ->queryActiveAndScheduledRelations()
            ->find();
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function mapProductRelationTransfer(SpyProductRelation $productRelationEntity)
    {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->fromArray($productRelationEntity->toArray(), true);

        $queryRuleBuilderSetTransfer = $this->mapPropelQueryBuilderRuleSetTransfer($productRelationEntity);

        $productRelationTransfer->setQuerySet($queryRuleBuilderSetTransfer);

        return $productRelationTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function mapPropelQueryBuilderRuleSetTransfer(SpyProductRelation $productRelationEntity)
    {
        $queryRuleBuilderSetTransfer = new PropelQueryBuilderRuleSetTransfer();

        $queryRuleBuilderSetTransfer->fromArray(
            $this->utilEncodingService->decodeJson($productRelationEntity->getQuerySetData(), true),
            true
        );

        return $queryRuleBuilderSetTransfer;
    }
}
