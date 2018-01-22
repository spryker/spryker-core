<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Dependency\QueryContainer;

class ProductRelationStorageToProductRelationQueryContainerBridge implements ProductRelationStorageToProductRelationQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    public function __construct($productRelationQueryContainer)
    {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
    }

    /**
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale)
    {
        return $this->productRelationQueryContainer->queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale);
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryAllProductRelations()
    {
        return $this->productRelationQueryContainer->queryAllProductRelations();
    }
}
