<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;

class ProductRelationMapper
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper
     */
    protected $productRelationTypeMapper;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\StoreRelationMapper
     */
    protected $storeRelationMapper;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\RuleSetMapper
     */
    protected $ruleSetMapper;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductMapper
     */
    protected $productMapper;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper $productRelationTypeMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\StoreRelationMapper $storeRelationMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\RuleSetMapper $ruleSetMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductMapper $productMapper
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        ProductRelationTypeMapper $productRelationTypeMapper,
        StoreRelationMapper $storeRelationMapper,
        RuleSetMapper $ruleSetMapper,
        ProductMapper $productMapper,
        ProductRelationToUtilEncodingInterface $utilEncodingService
    ) {
        $this->productRelationTypeMapper = $productRelationTypeMapper;
        $this->storeRelationMapper = $storeRelationMapper;
        $this->ruleSetMapper = $ruleSetMapper;
        $this->productMapper = $productMapper;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelation[] $productRelationEntities
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelationTransfers
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function mapProductRelationEntitiesToProductRelationTransfers(
        ObjectCollection $productRelationEntities,
        array $productRelationTransfers
    ): array {
        foreach ($productRelationEntities as $productRelationEntity) {
            $productRelationTransfers[] = $this->mapProductRelationEntityToProductRelationTransfer(
                $productRelationEntity,
                new ProductRelationTransfer()
            );
        }

        return $productRelationTransfers;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function mapProductRelationEntityToProductRelationTransfer(
        SpyProductRelation $productRelationEntity,
        ProductRelationTransfer $productRelationTransfer
    ): ProductRelationTransfer {
        $productRelationTransfer->fromArray($productRelationEntity->toArray(), true);
        $productRelationTransfer->setQuerySet(
            $this->ruleSetMapper->mapQuerySetDataToPropelQueryBuilderRuleSetTransfer(
                $productRelationEntity->getQuerySetData(),
                new PropelQueryBuilderRuleSetTransfer()
            )
        );
        $productRelationTypeTransfer = $this->productRelationTypeMapper
            ->mapProductRelationTypeEntityToProductRelationTypeTransfer(
                $productRelationEntity->getSpyProductRelationType(),
                new ProductRelationTypeTransfer()
            );
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        $productRelationEntity->resetPartialProductRelationStores();
        $productRelationTransfer->setStoreRelation(
            $this->storeRelationMapper->mapProductRelationStoreEntitiesToStoreRelationTransfer(
                $productRelationEntity->getProductRelationStores(),
                $this->createStoreRelationTransfer($productRelationTransfer)
            )
        );

        $productRelationEntity->resetPartialSpyProductRelationProductAbstracts();
        $productRelationTransfer = $this->productMapper->mapProductRelationRelatedProductEntitiesToProductRelationTransfer(
            $productRelationEntity->getSpyProductRelationProductAbstracts(),
            $productRelationTransfer
        );

        return $productRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function createStoreRelationTransfer(ProductRelationTransfer $productRelationTransfer): StoreRelationTransfer
    {
        return (new StoreRelationTransfer())
            ->setIdEntity($productRelationTransfer->getIdProductRelation());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation
     */
    public function mapProductRelationTransferToProductRelationEntity(
        ProductRelationTransfer $productRelationTransfer,
        SpyProductRelation $productRelationEntity
    ): SpyProductRelation {
        $productRelationEntity->fromArray($productRelationTransfer->toArray());
        $productRelationEntity->setFkProductRelationType($productRelationTransfer->getProductRelationType()->getIdProductRelationType());
        $productRelationEntity->setQuerySetData($this->encodeQuerySet($productRelationTransfer));

        return $productRelationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return string
     */
    protected function encodeQuerySet(ProductRelationTransfer $productRelationTransfer): string
    {
        $querySetTransfer = $productRelationTransfer->getQuerySet();
        $querySetData = [];

        if ($querySetTransfer !== null) {
            $querySetData = $querySetTransfer->toArray();
        }

        return $this->utilEncodingService->encodeJson(
            $querySetData
        );
    }
}
