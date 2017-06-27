<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use PDO;
use Propel\Runtime\Formatter\ArrayFormatter;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

class ProductAttributeManager implements ProductAttributeManagerInterface
{

    const DEFAULT_LOCALE = '_';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeLoaderInterface
     */
    protected $attributeLoader;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductAttributeGui\Business\Model\AttributeLoaderInterface $attributeFetcher
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        AttributeLoaderInterface $attributeFetcher
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->attributeLoader = $attributeFetcher;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        $query = $this->attributeLoader->queryProductAttributeValues($values);
        $results = $this->attributeLoader->load($query);

        return $results;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        $query = $this->attributeLoader->queryMetaAttributes($values);

        $data = $query->find();

        $results = [];
        foreach ($data as $entity) {
            $results[$entity->getKey()] = $entity->getIdProductAttributeKey();
        }

        return $results;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        $productAbstractEntity = $this->getProductAbstractEntity($idProductAbstract);

        $localizedAttributes = [];
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateProductAbstractAttributes($productAbstractEntity, $localizedAttributes);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText, $limit)
    {
        $results = [];
        $query = $this->productQueryContainer
            ->queryProductAttributeKey()
            ->limit($limit);

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        foreach ($query->find() as $entity) {
            $results[$entity->getIdProductAttributeKey()] = [
                'id' => $entity->getIdProductAttributeKey(),
                'value' => $entity->getKey(),
            ];
        }

        return $results;
    }

    /**
     * @param string $localizedAttributesJson
     *
     * @return array
     */
    protected function decodeJsonAttributes($localizedAttributesJson)
    {
        $attributesDecoded = (array)json_decode($localizedAttributesJson, true);  //TODO util

        return $attributesDecoded;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntity($idProductAbstract)
    {
        return $this->productQueryContainer->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->joinSpyProductAbstractLocalizedAttributes()
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAttributeEntity
     * @param array $localizedAttributes
     *
     * @return array
     */
    protected function generateProductAbstractAttributes(SpyProductAbstract $productAttributeEntity, array $localizedAttributes)
    {
        $attributes = $this->decodeJsonAttributes($productAttributeEntity->getAttributes());
        $attributes = [static::DEFAULT_LOCALE => $attributes] + $localizedAttributes;

        return $attributes;
    }

}
