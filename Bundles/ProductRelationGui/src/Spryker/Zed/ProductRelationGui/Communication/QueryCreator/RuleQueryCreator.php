<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\QueryCreator;

use ArrayObject;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\RuleQueryDataProviderTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\ProductRelationGui\Communication\Provider\MappingProviderInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerInterface;

class RuleQueryCreator implements RuleQueryCreatorInterface
{
    protected const COL_CATEGORY_NAME = 'category_name';
    protected const ALIAS_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES = 'spy_product_abstract_localized_attributes';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productAbstractQuery;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Communication\Provider\MappingProviderInterface
     */
    protected $mappingProvider;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerInterface
     */
    protected $propelQueryBuilderQueryContainer;

    /**
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface $localeFacade
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Spryker\Zed\ProductRelationGui\Communication\Provider\MappingProviderInterface $mappingProvider
     * @param \Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerInterface $propelQueryBuilderQueryContainer
     */
    public function __construct(
        ProductRelationGuiToLocaleFacadeInterface $localeFacade,
        SpyProductAbstractQuery $productAbstractQuery,
        MappingProviderInterface $mappingProvider,
        ProductRelationGuiToPropelQueryBuilderQueryContainerInterface $propelQueryBuilderQueryContainer
    ) {
        $this->localeFacade = $localeFacade;
        $this->productAbstractQuery = $productAbstractQuery;
        $this->mappingProvider = $mappingProvider;
        $this->propelQueryBuilderQueryContainer = $propelQueryBuilderQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ProductRelationTransfer $productRelationTransfer): ModelCriteria
    {
        $ruleQueryTransfer = $this->mapRuleQueryTransfer($productRelationTransfer);

        $query = $this->prepareQuery($productRelationTransfer->getQueryDataProvider());

        return $this->propelQueryBuilderQueryContainer->createQuery($query, $ruleQueryTransfer);
    }

    /**
     * @module Product
     * @module Category
     * @module ProductCategory
     *
     * @param \Generated\Shared\Transfer\RuleQueryDataProviderTransfer|null $dataProviderTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function prepareQuery(?RuleQueryDataProviderTransfer $dataProviderTransfer): ModelCriteria
    {
        $idLocale = $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();

        $query = $this->productAbstractQuery
            ->setModelAlias(static::ALIAS_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES)
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                    SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                ],
                [
                    SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                    $idLocale,
                ],
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [
                    SpyProductTableMap::COL_ID_PRODUCT,
                    SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                ],
                [
                    SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                    $idLocale,
                ],
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [
                    SpyProductCategoryTableMap::COL_FK_CATEGORY,
                    SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                ],
                [
                    SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                    $idLocale,
                ],
                Criteria::LEFT_JOIN
            )
            ->groupByIdProductAbstract()
            ->withColumn(
                'GROUP_CONCAT(DISTINCT ' . SpyCategoryAttributeTableMap::COL_NAME . ')',
                static::COL_CATEGORY_NAME
            );

        return $this->filterByProductAbstract($query, $dataProviderTransfer);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param \Generated\Shared\Transfer\RuleQueryDataProviderTransfer|null $dataProviderTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function filterByProductAbstract(
        ModelCriteria $query,
        ?RuleQueryDataProviderTransfer $dataProviderTransfer
    ): ModelCriteria {
        if (!$dataProviderTransfer || !$dataProviderTransfer->getIdProductAbstract()) {
            return $query;
        }

        return $query->filterByIdProductAbstract($dataProviderTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function mapRuleQueryTransfer(ProductRelationTransfer $productRelationTransfer): PropelQueryBuilderCriteriaTransfer
    {
        $propelQueryBuilderCriteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $propelQueryBuilderCriteriaTransfer->setRuleSet($productRelationTransfer->getQuerySet());
        $propelQueryBuilderCriteriaTransfer->setMappings(new ArrayObject($this->mappingProvider->getMappings()));

        return $propelQueryBuilderCriteriaTransfer;
    }
}
