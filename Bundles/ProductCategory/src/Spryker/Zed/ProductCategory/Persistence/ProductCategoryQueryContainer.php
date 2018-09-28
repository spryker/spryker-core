<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryPersistenceFactory getFactory()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{
    public const COL_CATEGORY_NAME = 'category_name';
    public const VIRTUAL_COLUMN_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @api
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappings()
    {
        return $this->getFactory()->createProductCategoryQuery();
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByCategoryId($idCategory)
    {
        return $this->getFactory()
            ->createProductCategoryQuery()
            ->filterByFkCategory($idCategory);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idCategory, $idProductAbstract)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkCategory($idCategory);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingByIdProduct($idProductAbstract)
    {
        $query = $this->queryProductCategoryMappings();
        $query->filterByFkProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale)
    {
        return $this->queryProductCategoryMappings()
            ->innerJoinSpyProductAbstract()
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyLocaleTableMap::COL_ID_LOCALE,
                $locale->getIdLocale(),
                Criteria::EQUAL
            )
            ->addAnd(
                SpyLocaleTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                'name'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                'id_product_abstract'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_SKU,
                'sku'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
                'product_order'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_ID_PRODUCT_CATEGORY,
                'id_product_category'
            )
            ->filterByFkCategory($idCategory)
            ->orderByFkProductAbstract();
    }

    /**
     * @api
     *
     * @param string $term
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTermForAssignment($term, $idCategory, LocaleTransfer $localeTransfer)
    {
        $query = $this->queryProductsAbstractBySearchTerm($term, $localeTransfer);
        $query->addJoin(
            [SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, $idCategory],
            [SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductCategoryTableMap::COL_FK_CATEGORY],
            Criteria::LEFT_JOIN
        )
            ->addAnd(
                SpyProductCategoryTableMap::COL_FK_CATEGORY,
                null,
                Criteria::ISNULL
            );

        return $query;
    }

    /**
     * @api
     *
     * @param string $term
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTerm($term, LocaleTransfer $locale)
    {
        $query = $this->getFactory()->createProductAbstractQuery();

        $query->addJoin(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::INNER_JOIN
        )
        ->addJoin(
            SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
            SpyLocaleTableMap::COL_ID_LOCALE,
            Criteria::INNER_JOIN
        )
        ->addAnd(
            SpyLocaleTableMap::COL_ID_LOCALE,
            $locale->getIdLocale(),
            Criteria::EQUAL
        )
        ->addAnd(
            SpyLocaleTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        )
        ->withColumn(
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            'name'
        )
        ->withColumn(
            SpyProductAbstractTableMap::COL_ATTRIBUTES,
            'abstract_attributes'
        )
        ->withColumn(
            SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
            'abstract_localized_attributes'
        );

        $query->groupByAttributes();
        $query->groupByIdProductAbstract();

        if (trim($term) !== '') {
            $term = '%' . mb_strtoupper($term) . '%';

            $query->where('UPPER(' . SpyProductAbstractTableMap::COL_SKU . ') LIKE ?', $term, PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryChildrenMappingsByCategoryNodeId($idCategoryNode)
    {
        return $this
            ->getFactory()
            ->createProductCategoryQuery()
            ->useSpyCategoryQuery()
                ->useNodeQuery()
                    ->useDescendantQuery()
                        ->filterByFkCategoryNode($idCategoryNode)
                    ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param array $idsCategoryNode
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByIdAbstractProductAndIdsCategoryNode(
        $idProductAbstract,
        array $idsCategoryNode
    ) {
        return $this
            ->queryProductCategoryMappings()
            ->filterByFkProductAbstract($idProductAbstract)
            ->useSpyCategoryQuery()
                ->useNodeQuery()
                    ->withColumn(
                        SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                        static::VIRTUAL_COLUMN_ID_CATEGORY_NODE
                    )
                    ->filterByIdCategoryNode($idsCategoryNode, Criteria::IN)
                ->endUse()
            ->endUse();
    }
}
