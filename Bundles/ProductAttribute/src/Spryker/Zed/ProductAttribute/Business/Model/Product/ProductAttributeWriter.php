<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig;

class ProductAttributeWriter implements ProductAttributeWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface $reader
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     */
    public function __construct(
        ProductAttributeReaderInterface $reader,
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToProductInterface $productFacade
    ) {
        $this->reader = $reader;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $productAbstractTransfer = $this->reader->getProductAbstractTransfer($idProductAbstract);
        $attributesToSave = $this->getAttributesDataToSave($attributes);

        $productAbstractTransfer->setAttributes(
            $this->getNonLocalizedAttributes($attributesToSave)
        );

        $this->updateLocalizedAttributeTransfers($attributesToSave, (array)$productAbstractTransfer->getLocalizedAttributes());
        $this->productFacade->saveProduct($productAbstractTransfer, []);
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $productConcreteTransfer = $this->reader->getProductTransfer($idProduct);
        $productAbstractTransfer = $this->reader->getProductAbstractTransfer($productConcreteTransfer->getFkProductAbstract());
        $attributesToSave = $this->getAttributesDataToSave($attributes);

        $productConcreteTransfer->setAttributes(
            $this->getNonLocalizedAttributes($attributesToSave)
        );

        $this->updateLocalizedAttributeTransfers($attributesToSave, (array)$productConcreteTransfer->getLocalizedAttributes());
        $this->productFacade->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);
    }

    /**
     * @param array $attributesToSave
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransferCollection
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    protected function updateLocalizedAttributeTransfers(array $attributesToSave, array $localizedAttributeTransferCollection)
    {
        unset($attributesToSave[ProductAttributeConfig::DEFAULT_LOCALE]);

        foreach ($localizedAttributeTransferCollection as $localizedAttributesTransfer) {
            $idLocale = $localizedAttributesTransfer->getLocale()->getIdLocale();
            $localizedDataToSave = $localizedAttributesTransfer->getAttributes();

            if (array_key_exists($idLocale, $attributesToSave)) {
                $localizedDataToSave = $attributesToSave[$idLocale];
            }

            $localizedAttributesTransfer->setAttributes($localizedDataToSave);
        }

        return $localizedAttributeTransferCollection;
    }

    /**
     * @param array $attributes
     * @param bool $returnKeysToRemove
     *
     * @return array
     */
    protected function getAttributesDataToSave(array $attributes, $returnKeysToRemove = false)
    {
        $attributeData = [];
        $keysToRemove = [];

        foreach ($attributes as $attribute) {
            $localeCode = $attribute[ProductAttributeConfig::LOCALE_CODE];
            $key = $attribute[ProductAttributeConfig::KEY];
            $value = trim($attribute['value']);

            if ($value !== '') {
                $attributeData[$localeCode][$key] = $value;
            } else {
                $keysToRemove[$localeCode][$key] = $key;
            }
        }

        if ($returnKeysToRemove) {
            return $keysToRemove;
        }

        return $attributeData;
    }

    /**
     * @param array $attributeData
     *
     * @return array
     */
    protected function getNonLocalizedAttributes(array $attributeData)
    {
        $productAbstractAttributes = [];
        if (array_key_exists(ProductAttributeConfig::DEFAULT_LOCALE, $attributeData)) {
            $productAbstractAttributes = $attributeData[ProductAttributeConfig::DEFAULT_LOCALE];
        }

        return $productAbstractAttributes;
    }

}
