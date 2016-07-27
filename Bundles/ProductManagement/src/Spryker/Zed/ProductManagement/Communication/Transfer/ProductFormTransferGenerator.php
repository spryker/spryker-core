<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Generated\Shared\Transfer\ZedProductPriceTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormGeneral;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormPrice;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormSeo;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Symfony\Component\Form\FormInterface;

class ProductFormTransferGenerator implements ProductFormTransferGeneratorInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     */
    public function __construct(ProductManagementToLocaleInterface $localeFacade, LocaleProvider $localeProvider)
    {
        $this->localeFacade = $localeFacade;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function buildProductAbstractTransfer(FormInterface $form)
    {
        $formData = $form->getData();
        $attributeValues = $this->convertAttributeArrayFromData($formData);
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $productAbstractTransfer = $this->createProductAbstractTransfer(
            $formData,
            $attributeValues[ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE]
        );

        $localizedData = $this->generateLocalizedData($localeCollection, $formData);

        foreach ($localizedData as $code => $data) {
            $localeTransfer = $this->localeFacade->getLocale($code);
            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $data,
                $attributeValues[$code],
                $localeTransfer
            );

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $priceTransfer = $this->buildProductPriceTransfer($form);

        $productAbstractTransfer->setPrice($priceTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param array $localeCollection
     * @param array $formData
     *
     * @return array
     */
    protected function generateLocalizedData(array $localeCollection, array $formData)
    {
        $localizedData = [];
        foreach ($localeCollection as $code) {
            $formName = ProductFormAdd::getGeneralFormName($code);
            $localizedData[$code] = $formData[$formName];
        }

        foreach ($localeCollection as $code) {
            $formName = ProductFormAdd::getSeoFormName($code);
            $localizedData[$code] = array_merge($localizedData[$code], $formData[$formName]);
        }

        return $localizedData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProductConcreteTransferFromData(ProductAbstractTransfer $productAbstractTransfer, array $formData)
    {
        $productConcreteTransfer = new ZedProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([]);
        $productConcreteTransfer->setSku($productAbstractTransfer->getSku() . '-' . rand(1, 999));
        $productConcreteTransfer->setIsActive(false);
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $attributeData = $formData[ProductFormAdd::GENERAL];
        foreach ($attributeData as $localeCode => $localizedAttributesData) {
            $localeTransfer = $this->localeProvider->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $localizedAttributesData,
                [],
                $localeTransfer
            );

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(array $data, array $attributes)
    {
        $attributes = array_filter($attributes);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($data[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT])
            ->setSku(
                $this->slugify($data[ProductFormAdd::FIELD_SKU])
            )
            ->setAttributes($attributes)
            ->setTaxSetId($data[ProductFormAdd::PRICE_AND_STOCK][ProductFormPrice::FIELD_TAX_RATE])
        ;


        return $productAbstractTransfer;
    }

    /**
     * @param array $data
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(array $data, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $abstractLocalizedAttributes = array_filter($abstractLocalizedAttributes);
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($data[ProductFormGeneral::FIELD_NAME]);
        $localizedAttributesTransfer->setDescription($data[ProductFormGeneral::FIELD_DESCRIPTION]);
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);
        $localizedAttributesTransfer->setMetaTitle($data[ProductFormSeo::FIELD_META_TITLE]);
        $localizedAttributesTransfer->setMetaKeywords($data[ProductFormSeo::FIELD_META_KEYWORDS]);
        $localizedAttributesTransfer->setMetaDescription($data[ProductFormSeo::FIELD_META_DESCRIPTION]);

        return $localizedAttributesTransfer;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function slugify($value)
    {
        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^a-zA-Z0-9 -]/", "", trim($value));
        $value = strtolower($value);
        $value = str_replace(' ', '-', $value);

        return $value;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function convertAttributeArrayFromData(array $data)
    {
        $attributes = [];
        $localeCollection = $this->localeProvider->getLocaleCollection(true);

        foreach ($localeCollection as $code) {
            $formName = ProductFormAdd::getAbstractAttributeFormName($code);
            foreach ($data[$formName] as $type => $values) {
                $attributes[$code][$type] = $values['value'];
            }
        }

        return $attributes;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer
     */
    public function buildProductPriceTransfer(FormInterface $form)
    {
        $price = $form->get(ProductFormAdd::PRICE_AND_STOCK)->get(ProductFormPrice::FIELD_PRICE)->getData();
        $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();

        $priceTransfer = (new ZedProductPriceTransfer())
            ->setIdProduct($idProductAbstract)
            ->setPrice($price);

        return $priceTransfer;

    }

}
