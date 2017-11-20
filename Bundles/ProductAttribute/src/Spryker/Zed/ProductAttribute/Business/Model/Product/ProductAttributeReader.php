<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeTableMap;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

class ProductAttributeReader implements ProductAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface
     */
    protected $attributeMapper;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface $productAttributeQueryContainer
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface $attributeMapper
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     */
    public function __construct(
        ProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        ProductAttributeMapperInterface $attributeMapper,
        ProductAttributeToProductInterface $productFacade
    ) {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
        $this->attributeMapper = $attributeMapper;
        $this->productFacade = $productFacade;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function getMetaAttributesByValues(array $values)
    {
        $query = $this->queryMetaAttributes($values);
        $query->setFormatter(new ArrayFormatter());

        return $this->attributeMapper->mapMetaAttributes($query->find());
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10)
    {
        $query = $this->querySuggestKeys($searchText, $limit);

        return $this->attributeMapper->metaAttributeSuggestKeys($query->find());
    }

    /**
     * @param array $productAttributes
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria|\Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function queryMetaAttributes(array $productAttributes)
    {
        $keys = $this->attributeMapper->extractKeysFromAttributes($productAttributes);

        $query = $this->productAttributeQueryContainer
            ->queryMetaAttributesByKeys($keys)
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeQueryContainer::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeQueryContainer::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeQueryContainer::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeQueryContainer::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeQueryContainer::INPUT_TYPE);

        return $query;
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function querySuggestKeys($searchText, $limit = 10)
    {
        $query = $this->productAttributeQueryContainer
            ->querySuggestKeys($searchText, $limit)
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeQueryContainer::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeQueryContainer::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeQueryContainer::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeQueryContainer::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeQueryContainer::INPUT_TYPE)
            ->orderByKey()
            ->setFormatter(new ArrayFormatter());

        return $query;
    }
}
