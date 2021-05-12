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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationType();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryAllProductRelations();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationTypeByKey($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function queryProductsWithCategoriesByFkLocale($idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductRelation($idProductRelation);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductRelationType
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdRelationType($idProductRelationType);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $relationKey
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductAbstractAndRelationKey($idProductAbstract, $relationKey);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductRelation
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdRelationAndIdProduct($idProductRelation, $idProductAbstract);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdProductRelation($idProductRelation);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationsWithProductCount($idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getRulePropelQuery(ProductRelationTransfer $productRelationTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveProductRelations();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryActiveProductRelationProductAbstract();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveAndScheduledRelations();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryRulePropelQueryWithLocalizedProductData(ProductRelationTransfer $productRelationTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idLocale
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation($idLocale, $idProductRelation);
}
