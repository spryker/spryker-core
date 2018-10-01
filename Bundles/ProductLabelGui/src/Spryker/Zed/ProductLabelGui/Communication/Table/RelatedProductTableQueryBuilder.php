<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;
use Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig;

class RelatedProductTableQueryBuilder implements RelatedProductTableQueryBuilderInterface
{
    public const RESULT_FIELD_PRODUCT_ABSTRACT_NAME = 'abstract_product_name';
    public const RESULT_FIELD_PRODUCT_ABSTRACT_PRICE = 'abstract_product_price';
    public const RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV = 'abstract_product_category_names_csv';
    public const RESULT_FIELD_PRODUCT_ABSTRACT_RELATION_COUNT = 'abstract_product_relation_count';
    public const RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV = 'concrete_product_states_csv';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface
     */
    protected $productLabelGuiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig
     */
    protected $bundleConfig;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface $productLabelGuiQueryContainer
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig $bundleConfig
     */
    public function __construct(
        ProductLabelGuiToProductQueryContainerInterface $productQueryContainer,
        ProductLabelGuiQueryContainerInterface $productLabelGuiQueryContainer,
        ProductLabelGuiToLocaleInterface $localeFacade,
        ProductLabelGuiConfig $bundleConfig
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productLabelGuiQueryContainer = $productLabelGuiQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function buildAvailableProductQuery($idProductLabel = null)
    {
        $query = $this->build($idProductLabel);

        $query->where(sprintf(
            '%s IS NULL',
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL
        ));

        return $query;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function buildAssignedProductQuery($idProductLabel = null)
    {
        $query = $this->build($idProductLabel);

        $query->where(sprintf(
            '%s IS NOT NULL',
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL
        ));

        return $query;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function build($idProductLabel = null)
    {
        $query = $this->productQueryContainer->queryProductAbstract();
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $this->addProductName($query, $localeTransfer);
        $this->addProductCategories($query, $localeTransfer);
        $this->addConcreteProductStates($query);
        $this->addRelation($query, $idProductLabel);
        $this->addRelationCount($query);

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function addProductName(SpyProductAbstractQuery $query, LocaleTransfer $localeTransfer)
    {
        $query
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->withColumn(
                    SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                    static::RESULT_FIELD_PRODUCT_ABSTRACT_NAME
                )
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function addProductCategories(SpyProductAbstractQuery $query, LocaleTransfer $localeTransfer)
    {
        $query
            ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                        ->withColumn(
                            sprintf('GROUP_CONCAT(%s)', SpyCategoryAttributeTableMap::COL_NAME),
                            static::RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV
                        )
                        ->filterByFkLocale($localeTransfer->getIdLocale())
                        ->_or()
                        ->filterByFkLocale(null)
                    ->endUse()
                ->endUse()
                ->groupByFkProductAbstract()
            ->endUse();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     *
     * @return void
     */
    protected function addConcreteProductStates(SpyProductAbstractQuery $query)
    {
        $query
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(
                    sprintf('GROUP_CONCAT(%s)', SpyProductTableMap::COL_IS_ACTIVE),
                    static::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV
                )
            ->endUse()
            ->groupByIdProductAbstract();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param int|null $idProductLabel
     *
     * @return void
     */
    protected function addRelation(SpyProductAbstractQuery $query, $idProductLabel)
    {
        $relationJoin = new Join(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::LEFT_JOIN
        );

        $query->addJoinObject($relationJoin, 'relationJoin');

        $query->addJoinCondition(
            'relationJoin',
            sprintf(
                '%s = %s',
                SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL,
                $idProductLabel ?: 'NULL'
            )
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     *
     * @return void
     */
    protected function addRelationCount(SpyProductAbstractQuery $query)
    {
        $subQuery = $this
            ->productLabelGuiQueryContainer
            ->queryProductAbstractRelations()
            ->where(sprintf(
                '%s = %s',
                SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT
            ))
            ->groupByFkProductAbstract()
            ->addSelfSelectColumns()
            ->clearSelectColumns()
            ->withColumn(
                sprintf('COUNT(%s)', SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT),
                static::RESULT_FIELD_PRODUCT_ABSTRACT_RELATION_COUNT
            )
            ->select([
                static::RESULT_FIELD_PRODUCT_ABSTRACT_RELATION_COUNT => static::RESULT_FIELD_PRODUCT_ABSTRACT_RELATION_COUNT,
            ]);

        $params = [];
        $query->withColumn(
            sprintf('(%s)', $subQuery->createSelectSql($params)),
            static::RESULT_FIELD_PRODUCT_ABSTRACT_RELATION_COUNT
        );
    }
}
