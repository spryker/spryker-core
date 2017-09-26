<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductRelationQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationType();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryAllProductRelations();

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationTypeByKey($key);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function queryProductsWithCategoriesByFkLocale($idLocale);

    /**
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductRelation($idProductRelation);

    /**
     * @api
     *
     * @param int $idProductRelationType
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdRelationType($idProductRelationType);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $relationKey
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductAbstractAndRelationKey($idProductAbstract, $relationKey);

    /**
     * @api
     *
     * @param int $idProductRelation
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdRelationAndIdProduct($idProductRelation, $idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdProductRelation($idProductRelation);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationsWithProductCount($idLocale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getRulePropelQuery(ProductRelationTransfer $productRelationTransfer);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveProductRelations();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryActiveProductRelationProductAbstract();

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @api
     *
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveAndScheduledRelations();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryRulePropelQueryWithLocalizedProductData(ProductRelationTransfer $productRelationTransfer);

    /**
     * @api
     *
     * @param int $idLocale
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation($idLocale, $idProductRelation);

}
