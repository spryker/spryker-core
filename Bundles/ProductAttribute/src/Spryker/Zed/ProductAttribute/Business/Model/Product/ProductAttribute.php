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
    protected $productAttributeReader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface
     */
    protected $productAttributeMapper;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface $productAttributeReader
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface $productAttributeMapper
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface $productReader
     */
    public function __construct(
        ProductAttributeReaderInterface $productAttributeReader,
        ProductAttributeMapperInterface $productAttributeMapper,
        ProductReaderInterface $productReader
    ) {
        $this->productAttributeReader = $productAttributeReader;
        $this->productAttributeMapper = $productAttributeMapper;
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

        return $this->productAttributeReader->getMetaAttributesByValues($values);
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

        return $this->productAttributeReader->getMetaAttributesByValues($values);
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
