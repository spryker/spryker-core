<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Dependency\QueryContainer;

use Generated\Shared\Transfer\ProductRelationTransfer;

class ProductRelationGuiToProductRelationQueryContainerBridge implements ProductRelationGuiToProductRelationQueryContainerInterface
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
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryRulePropelQueryWithLocalizedProductData(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->productRelationQueryContainer
            ->queryRulePropelQueryWithLocalizedProductData($productRelationTransfer);
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function queryProductsWithCategoriesByFkLocale($idLocale)
    {
        return $this->productRelationQueryContainer->queryProductsWithCategoriesByFkLocale($idLocale);
    }

    /**
     * @param int $idLocale
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation($idLocale, $idProductRelation)
    {
        return $this->productRelationQueryContainer->queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation($idLocale, $idProductRelation);
    }
}
