<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAttributeManager implements ProductAttributeManagerInterface
{

    const DEFAULT_LOCALE = 'default';

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
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        $productAbstractEntity = $this->getProductAbstractEntity($idProductAbstract);

        $localizedAttributes = [];
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateAttributes($productAbstractEntity, $localizedAttributes);
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
     * @return array|mixed|\Orm\Zed\Product\Persistence\SpyProductAbstract
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
     * @param array \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[] $localizedAttributes
     *
     * @return array
     */
    protected function generateAttributes(SpyProductAbstract $productAttributeEntity, array $localizedAttributes)
    {
        $attributes = $this->decodeJsonAttributes($productAttributeEntity->getAttributes());
        $attributes = [static::DEFAULT_LOCALE => $attributes] + $localizedAttributes;

        return $attributes;
    }

}
