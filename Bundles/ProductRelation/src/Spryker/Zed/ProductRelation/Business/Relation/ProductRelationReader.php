<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationType;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductRelationReader implements ProductRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer
    ) {

        $this->productRelationQueryContainer = $productRelationQueryContainer;
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
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationType[]
     */
    protected function findProductRelationTemplates()
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationType()
            ->find();
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
}
