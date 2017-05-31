<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;
use Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig;

class RelatedProductTableQueryBuilder implements RelatedProductTableQueryBuilderInterface
{

    const RESULT_FIELD_ABSTRACT_PRODUCT_NAME = 'abstract_product_name';
    const RESULT_FIELD_ABSTRACT_PRODUCT_PRICE = 'abstract_product_price';
    const RESULT_FIELD_ABSTRACT_PRODUCT_LABEL_HAS_RELATION_FLAG = 'abstract_product_has_label_relation_flag';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerInterface
     */
    protected $productQueryContainer;

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
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig $bundleConfig
     */
    public function __construct(
        ProductLabelGuiToProductQueryContainerInterface $productQueryContainer,
        ProductLabelGuiToLocaleInterface $localeFacade,
        ProductLabelGuiConfig $bundleConfig
    ) {
        $this->productQueryContainer = $productQueryContainer;
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

        $this->addProductName($query);
        $this->addProductPrice($query);
        $this->addRelation($query, $idProductLabel);

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $query
     *
     * @return void
     */
    protected function addProductName(SpyProductAbstractQuery $query)
    {
        $query
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->withColumn(
                    SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                    static::RESULT_FIELD_ABSTRACT_PRODUCT_NAME
                )
                ->filterByFkLocale($this->localeFacade->getCurrentLocale()->getIdLocale())
            ->endUse();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $query
     *
     * @return void
     */
    protected function addProductPrice(SpyProductAbstractQuery $query)
    {
        $query
            ->usePriceProductQuery()
                ->withColumn(
                    SpyPriceProductTableMap::COL_PRICE,
                    static::RESULT_FIELD_ABSTRACT_PRODUCT_PRICE
                )
                ->usePriceTypeQuery()
                    ->filterByName($this->bundleConfig->getDefaultPriceType())
                ->endUse()
            ->endUse();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $query
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

}
