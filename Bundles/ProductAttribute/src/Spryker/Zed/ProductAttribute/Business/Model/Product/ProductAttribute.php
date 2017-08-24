<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

use Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

class ProductAttribute implements ProductAttributeInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface
     */
    protected $mapper;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface $attributeReader
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface $attributeMapper
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface $productReader
     */
    public function __construct(
        ProductAttributeReaderInterface $attributeReader,
        ProductAttributeMapperInterface $attributeMapper,
        ProductReaderInterface $productReader
    ) {
        $this->reader = $attributeReader;
        $this->mapper = $attributeMapper;
        $this->productReader = $productReader;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract)
    {
        $values = $this->getProductAbstractAttributes($idProductAbstract);
        return $this->reader->getMetaAttributesByValues($values);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract)
    {
        $productAbstractTransfer = $this->productReader->getProductAbstractTransfer($idProductAbstract);

        return $this->generateAttributes(
            (array)$productAbstractTransfer->getAttributes(),
            (array)$productAbstractTransfer->getLocalizedAttributes()
        );
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
        $productTransfer = $this->productReader->getProductTransfer($idProduct);

        return $this->generateAttributes(
            (array)$productTransfer->getAttributes(),
            (array)$productTransfer->getLocalizedAttributes()
        );
    }

    /**
     * @param array $defaultAttributes
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return array
     */
    protected function generateAttributes(array $defaultAttributes, array $localizedAttributes)
    {
        $result = [];
        foreach ($localizedAttributes as $localizedAttributeTransfer) {
            $localeName = $localizedAttributeTransfer->getLocale()->getLocaleName();
            $result[$localeName] = $localizedAttributeTransfer->getAttributes();
        }

        $result[ProductAttributeConfig::DEFAULT_LOCALE] = $defaultAttributes;

        ksort($result);

        return $result;
    }

}
