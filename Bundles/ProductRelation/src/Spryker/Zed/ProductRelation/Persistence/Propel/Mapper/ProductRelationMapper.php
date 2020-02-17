<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;

class ProductRelationMapper
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper
     */
    protected $productRelationTypeMapper;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper $productRelationTypeMapper
     */
    public function __construct(ProductRelationTypeMapper $productRelationTypeMapper)
    {
        $this->productRelationTypeMapper = $productRelationTypeMapper;
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
        $productRelationTypeEntity = $productRelationEntity->getSpyProductRelationType();
        $productRelationTypeTransfer = $this->productRelationTypeMapper
            ->mapProductRelationTypeEntityToProductRelationTypeTransfer(
                $productRelationTypeEntity,
                new ProductRelationTypeTransfer()
            );
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

        return $productRelationTransfer;
    }
}
