<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Product;

use ArrayObject;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeServiceInterface;
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
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeServiceInterface
     */
    protected $sanitizeService;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface $reader
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface $productReader
     * @param \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeServiceInterface $sanitizeService
     */
    public function __construct(
        ProductAttributeReaderInterface $reader,
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToProductInterface $productFacade,
        ProductReaderInterface $productReader,
        ProductAttributeToUtilSanitizeServiceInterface $sanitizeService
    ) {
        $this->reader = $reader;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productReader = $productReader;
        $this->sanitizeService = $sanitizeService;
    }

    /**
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes)
    {
        $productAbstractTransfer = $this->productReader->getProductAbstractTransfer($idProductAbstract);
        $attributesToSave = $this->getAttributesDataToSave($attributes);
        $nonLocalizedAttributes = $this->getNonLocalizedAttributes($attributesToSave);

        $productAbstractTransfer->setAttributes(
            $nonLocalizedAttributes
        );

        $localizedAttributes = $this->updateLocalizedAttributeTransfers($attributesToSave, (array)$productAbstractTransfer->getLocalizedAttributes());
        $productAbstractTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $this->productFacade->saveProductAbstract($productAbstractTransfer);
        $this->productFacade->touchProductAbstract($productAbstractTransfer->getIdProductAbstract());
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes)
    {
        $productConcreteTransfer = $this->productReader->getProductTransfer($idProduct);
        $attributesToSave = $this->getAttributesDataToSave($attributes);
        $nonLocalizedAttributes = $this->getNonLocalizedAttributes($attributesToSave);

        $productConcreteTransfer->setAttributes(
            $nonLocalizedAttributes
        );

        $localizedAttributes = $this->updateLocalizedAttributeTransfers($attributesToSave, (array)$productConcreteTransfer->getLocalizedAttributes());
        $productConcreteTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $this->productFacade->saveProductConcrete($productConcreteTransfer);
        $this->productFacade->touchProductConcrete($productConcreteTransfer->getIdProductConcrete());
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
            $localeName = $localizedAttributesTransfer->getLocale()->getLocaleName();
            $localizedDataToSave = [];

            if (array_key_exists($localeName, $attributesToSave)) {
                $localizedDataToSave = $attributesToSave[$localeName];
            }

            $localizedAttributesTransfer->setAttributes($localizedDataToSave);
        }

        return $localizedAttributeTransferCollection;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributesDataToSave(array $attributes)
    {
        $attributeData = [];
        $keysToRemove = [];

        foreach ($attributes as $attribute) {
            $key = $attribute[ProductAttributeKeyTransfer::KEY];
            $localeCode = $attribute['locale_code'];
            $value = $this->sanitizeString($attribute['value']);

            if ($value === '' || $value === false) {
                $keysToRemove[$localeCode][$key] = $key;
                continue;
            }

            $attributeData[$localeCode][$key] = $value;
        }

        return $attributeData;
    }

    /**
     * @param string $string
     *
     * @return string|bool
     */
    protected function sanitizeString(string $string)
    {
        $result = trim($string);

        return $this->sanitizeService->escapeHtml($string);
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
