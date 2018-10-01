<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Rule\Query;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer;
use Generated\Shared\Transfer\RuleQueryDataProviderTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface;
use Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToProductInterface;

class ProductQuery implements QueryInterface
{
    public const COL_CATEGORY_NAME = 'category_name';
    public const ALIAS_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES = 'spy_product_abstract_localized_attributes';

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToProductInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToProductInterface $productQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductRelationToProductInterface $productQueryContainer,
        ProductRelationToLocaleInterface $localeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array|\Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer
     */
    public function getMappings()
    {
        $mapping = $this->buildProductMapping();
        $mapping = array_merge($mapping, $this->buildProductAttributeMap());

        return $mapping;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    protected function buildProductMapping()
    {
        $mapping = [];

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_sku');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractTableMap::COL_SKU,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_name');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
        $propelQueryBuilderCriteriaMappingTransfer->setAlias('product_created_at');
        $propelQueryBuilderCriteriaMappingTransfer->setColumns([
            SpyProductAbstractTableMap::COL_CREATED_AT,
            SpyProductTableMap::COL_CREATED_AT,
        ]);

        $mapping[] = $propelQueryBuilderCriteriaMappingTransfer;

        return $mapping;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer[]
     */
    protected function buildProductAttributeMap()
    {
        $productAttributeKeys = $this->findProductAttributes();

        $attributeMap = [];
        foreach ($productAttributeKeys as $productAttributeKeyEntity) {
            $attributeKey = $this->buildAttributeKey($productAttributeKeyEntity->getKey());

            $propelQueryBuilderCriteriaMappingTransfer = new PropelQueryBuilderCriteriaMappingTransfer();
            $propelQueryBuilderCriteriaMappingTransfer->setAlias($attributeKey);
            $propelQueryBuilderCriteriaMappingTransfer->setColumns([
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                SpyProductTableMap::COL_ATTRIBUTES,
                SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES,
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
            ]);

            $attributeMap[] = $propelQueryBuilderCriteriaMappingTransfer;
        }

        return $attributeMap;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQueryDataProviderTransfer|null $dataProviderTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function prepareQuery(?RuleQueryDataProviderTransfer $dataProviderTransfer = null)
    {
        $idLocale = $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();

        $query = $this->productQueryContainer
            ->queryProductAbstract();

        $query
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

        return $this->filterProductAbstractId($query, $dataProviderTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param \Generated\Shared\Transfer\RuleQueryDataProviderTransfer|null $ruleQueryDataProviderTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function filterProductAbstractId(
        ModelCriteria $query,
        ?RuleQueryDataProviderTransfer $ruleQueryDataProviderTransfer = null
    ) {
        if (!$ruleQueryDataProviderTransfer || !$ruleQueryDataProviderTransfer->getIdProductAbstract()) {
            return $query;
        }

        $ruleQueryDataProviderTransfer->requireIdProductAbstract();

        $query->filterByIdProductAbstract($ruleQueryDataProviderTransfer->getIdProductAbstract());

        return $query;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findProductAttributes()
    {
        $productAttributeKeys = $this->productQueryContainer
            ->queryProductAttributeKey()
            ->find();

        return $productAttributeKeys;
    }

    /**
     * @param string $persistedAttributeKey
     *
     * @return string
     */
    protected function buildAttributeKey($persistedAttributeKey)
    {
        return 'product.json.' . $persistedAttributeKey;
    }
}
