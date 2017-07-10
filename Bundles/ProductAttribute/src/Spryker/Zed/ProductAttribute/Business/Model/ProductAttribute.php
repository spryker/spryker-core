<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

class ProductAttribute implements ProductAttributeInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeReaderInterface
     */
    protected $attributeReader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeWriterInterface
     */
    protected $attributeWriter;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\AttributeMapperInterface
     */
    protected $attributeMapper;

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
        $this->attributeReader = $attributeReader;
        $this->attributeWriter = $attributeWriter;
        $this->attributeMapper = $attributeMapper;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        return $this->attributeReader->getAttributesByValues($values);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributeValues($idProductAbstract);
        return $this->attributeReader->getMetaAttributesByValues($values);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstract($idProductAbstract)
    {
        $entity = $this->attributeReader->getProductAbstractEntity($idProductAbstract);
        $productAbstractTransfer = new ProductAbstractTransfer();

        if (!$entity) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setIdProductAbstract($entity->getIdProductAbstract());
        $productAbstractTransfer->setSku($entity->getSku());

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        $productAbstractEntity = $this->attributeReader->getProductAbstractEntity($idProductAbstract);

        $localizedAttributes = [];
        foreach ($productAbstractEntity->getSpyProductAbstractLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->attributeMapper->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
            $localizedAttributes[$localizedAttributeEntity->getFkLocale()] = $attributesDecoded;
        }

        return $this->generateAttributes($productAbstractEntity->getAttributes(), $localizedAttributes);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProduct($idProduct)
    {
        $entity = $this->attributeReader->getProductEntity($idProduct);
        $productTransfer = new ProductConcreteTransfer();

        if (!$entity) {
            return $productTransfer;
        }

        $productTransfer->setIdProductConcrete($entity->getIdProduct());
        $productTransfer->setFkProductAbstract($entity->getFkProductAbstract());
        $productTransfer->setSku($entity->getSku());

        return $productTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributes($idProduct)
    {
        $values = $this->getProductAttributeValues($idProduct);
        return $this->attributeReader->getAttributesByValues($values);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct)
    {
        $values = $this->getProductAttributeValues($idProduct);
        return $this->attributeReader->getMetaAttributesByValues($values);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct)
    {
        $productEntity = $this->attributeReader->getProductEntity($idProduct);

        $localizedAttributes = [];
        foreach ($productEntity->getSpyProductLocalizedAttributess() as $localizedAttributeEntity) {
            $attributesDecoded = $this->attributeMapper->decodeJsonAttributes($localizedAttributeEntity->getAttributes());
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
        $attributes = $this->attributeMapper->decodeJsonAttributes($productAttributesJson);
        $attributes = [ProductAttributeConfig::DEFAULT_LOCALE => $attributes] + $localizedAttributes;

        ksort($attributes);

        return $attributes;
    }

}
