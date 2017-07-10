<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model;

use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

class ProductAttribute implements ProductAttributeInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeWriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeMapperInterface
     */
    protected $mapper;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\AttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductAttribute\Business\Model\AttributeWriterInterface $attributeWriter
     * @param \Spryker\Zed\ProductAttribute\Business\Model\AttributeMapperInterface $attributeMapper
     */
    public function __construct(
        AttributeReaderInterface $attributeReader,
        AttributeWriterInterface $attributeWriter,
        AttributeMapperInterface $attributeMapper
    ) {
        $this->reader = $attributeReader;
        $this->writer = $attributeWriter;
        $this->mapper = $attributeMapper;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        return $this->reader->getAttributesByValues($values);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        return $this->reader->getMetaAttributesByValues($values);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        $productAbstractEntity = $this->reader->getProductAbstractEntity($idProductAbstract);

        $localizedAttributes = [];
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->mapper->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateAttributes($productAbstractEntity->getAttributes(), $localizedAttributes);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributes($idProduct)
    {
        $values = $this->getProductAttributeValues($idProduct);
        return $this->reader->getAttributesByValues($values);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct)
    {
        $values = $this->getProductAttributeValues($idProduct);
        return $this->reader->getMetaAttributesByValues($values);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct)
    {
        $productEntity = $this->reader->getProductEntity($idProduct);

        $localizedAttributes = [];
        foreach ($productEntity->getSpyProductLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->mapper->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateAttributes($productEntity->getAttributes(), $localizedAttributes);
    }

    /**
     * @param string $productAttributesJson
     * @param array $localizedAttributes
     *
     * @return array
     */
    protected function generateAttributes($productAttributesJson, array $localizedAttributes)
    {
        $attributes = $this->mapper->decodeJsonAttributes($productAttributesJson);
        $attributes = [ProductAttributeConfig::DEFAULT_LOCALE => $attributes] + $localizedAttributes;

        ksort($attributes);

        return $attributes;
    }

}
