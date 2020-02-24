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
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper $productRelationTypeMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\StoreRelationMapper $storeRelationMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\RuleSetMapper $ruleSetMapper
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductMapper $productMapper
     */
    public function __construct(
        ProductRelationTypeMapper $productRelationTypeMapper,
        StoreRelationMapper $storeRelationMapper,
        RuleSetMapper $ruleSetMapper,
        ProductMapper $productMapper
    ) {
        $this->productRelationTypeMapper = $productRelationTypeMapper;
        $this->storeRelationMapper = $storeRelationMapper;
        $this->ruleSetMapper = $ruleSetMapper;
        $this->productMapper = $productMapper;
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
        $productRelationTransfer->setStoreRelation(
            $this->storeRelationMapper->mapPaymentMethodStoreEntitiesToStoreRelationTransfer(
                $productRelationEntity->getProductRelationStores(),
                new StoreRelationTransfer()
            )
        );
        $productRelationTransfer = $this->productMapper->mapProductRelationRelatedProductEntitiesToProductRelationTransfer(
            $productRelationEntity->getSpyProductRelationProductAbstracts(),
            $productRelationTransfer
        );

        return $productRelationTransfer;
    }
}
