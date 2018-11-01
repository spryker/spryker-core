<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Generated\Shared\Transfer\ProductRelationRelatedProductTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationType;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductRelationReader implements ProductRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService
    ) {

        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypeList()
    {
        $productRelationTypeCollection = $this->findProductRelationTemplates();

        $productRelationTypes = [];
        foreach ($productRelationTypeCollection as $productRelationTypeEntity) {
            $productRelationTypes[] = $this->mapProductRelationTypeTransfer($productRelationTypeEntity);
        }

        return $productRelationTypes;
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById($idProductRelation)
    {
        $productRelationEntity = $this->findProductRelationByIdProductRelation($idProductRelation);

        if ($productRelationEntity === null) {
            return null;
        }

        $productRelationTransfer = $this->mapProductRelationTransfer($productRelationEntity);

        $productRelationTransfer = $this->mapProductRelationRelatedProducts(
            $productRelationEntity,
            $productRelationTransfer
        );

        $productRelationTypeTransfer = $this->mapProductRelationTypeTransfer(
            $productRelationEntity->getSpyProductRelationType()
        );

        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        return $productRelationTransfer;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationType[]
     */
    protected function findProductRelationTemplates()
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationType()
            ->find();
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation|null
     */
    protected function findProductRelationByIdProductRelation($idProductRelation)
    {
        $productRelationEntity = $this->productRelationQueryContainer
            ->queryProductRelationByIdProductRelation($idProductRelation)
            ->findOne();

        return $productRelationEntity;
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

        $ruleQuerySetTransfer = $this->mapRuleQueryTransfer($productRelationEntity);
        $productRelationTransfer->setQuerySet($ruleQuerySetTransfer);

        return $productRelationTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelationType $productRelationTypeEntity
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer
     */
    protected function mapProductRelationTypeTransfer(SpyProductRelationType $productRelationTypeEntity)
    {
        $productRelationTypeTransfer = new ProductRelationTypeTransfer();
        $productRelationTypeTransfer->fromArray($productRelationTypeEntity->toArray(), true);

        return $productRelationTypeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function mapRuleQueryTransfer(SpyProductRelation $productRelationEntity)
    {
        $propelQueryBuilderRuleSetTransfer = new PropelQueryBuilderRuleSetTransfer();
        if ($productRelationEntity->getQuerySetData()) {
            $propelQueryBuilderRuleSetTransfer->fromArray(
                $this->utilEncodingService->decodeJson($productRelationEntity->getQuerySetData(), true),
                true
            );
        }

        return $propelQueryBuilderRuleSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function mapProductRelationRelatedProducts(
        SpyProductRelation $productRelationEntity,
        ProductRelationTransfer $productRelationTransfer
    ) {
        foreach ($productRelationEntity->getSpyProductRelationProductAbstracts() as $productRelationProductAbstractEntity) {
            $productRelationTransfer->addRelatedProduct(
                $this->mapProductRelationRelatedProduct($productRelationProductAbstractEntity)
            );
        }

        return $productRelationTransfer;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstract $productRelationProductAbstractEntity
     *
     * @return \Generated\Shared\Transfer\ProductRelationRelatedProductTransfer
     */
    protected function mapProductRelationRelatedProduct(SpyProductRelationProductAbstract $productRelationProductAbstractEntity)
    {
        $productRelationRelatedProductTransfer = new ProductRelationRelatedProductTransfer();
        $productRelationRelatedProductTransfer->fromArray($productRelationProductAbstractEntity->toArray(), true);

        return $productRelationRelatedProductTransfer;
    }
}
