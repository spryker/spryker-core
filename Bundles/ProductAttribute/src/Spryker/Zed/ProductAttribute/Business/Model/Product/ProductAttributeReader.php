<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

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
    public function getAttributesByValues(array $values)
    {
        $query = $this->queryAttributeValues($values);

        return $this->loadPdoStatement($query);
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
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductAbstractNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function getProductAbstractTransfer($idProductAbstract)
    {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            throw new ProductAbstractNotFoundException(sprintf(
                'Product abstract with id "%s" not found',
                $idProductAbstract
            ));
        }

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\ProductAttribute\Business\Model\Exception\ProductConcreteNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function getProductTransfer($idProduct)
    {
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProduct);

        if (!$productConcreteTransfer) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Product concrete with id "%s" not found',
                $idProduct
            ));
        }

        return $productConcreteTransfer;
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

        return $this->attributeMapper->maSuggestKeys($query->find());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $query
     *
     * @return array
     */
    protected function loadPdoStatement(Criteria $query)
    {
        $pdoStatement = $query->doSelect();

        $results = [];
        while ($data = $pdoStatement->fetch()) {
            $item = $this->attributeMapper->hydrateAttributeItem($data);
            $results[] = $item;
        }

        return $results;
    }

    /**
     * @param array $productAttributes
     * @param bool|null $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function queryAttributeValues(array $productAttributes = [], $isSuper = null)
    {
        $keys = $this->attributeMapper->extractKeysFromAttributes($productAttributes);

        $query = $this->productAttributeQueryContainer
            ->queryAttributeValues($keys, $isSuper)
            ->clearSelectColumns()
            ->withColumn(SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY, 'id_product_attribute_key')
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'id_product_management_attribute')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE, 'id_product_management_attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VALUE_TRANSLATION, 'id_product_management_attribute_value_translation')
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, 'attribute_key')
            ->withColumn(SpyProductManagementAttributeValueTableMap::COL_VALUE, 'attribute_value')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE, 'fk_locale')
            ->withColumn(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION, 'translation')
            ->orderBy(SpyProductAttributeKeyTableMap::COL_KEY)
            ->orderBy(SpyProductManagementAttributeValueTranslationTableMap::COL_FK_LOCALE)
            ->orderBy(SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION);

        return $query;
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
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeConfig::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeConfig::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeConfig::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeConfig::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeConfig::INPUT_TYPE);

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
            ->withColumn(SpyProductAttributeKeyTableMap::COL_KEY, ProductAttributeConfig::KEY)
            ->withColumn(SpyProductAttributeKeyTableMap::COL_IS_SUPER, ProductAttributeConfig::IS_SUPER)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, ProductAttributeConfig::ATTRIBUTE_ID)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_ALLOW_INPUT, ProductAttributeConfig::ALLOW_INPUT)
            ->withColumn(SpyProductManagementAttributeTableMap::COL_INPUT_TYPE, ProductAttributeConfig::INPUT_TYPE)
            ->orderByKey()
            ->setFormatter(new ArrayFormatter());

        return $query;
    }

}
