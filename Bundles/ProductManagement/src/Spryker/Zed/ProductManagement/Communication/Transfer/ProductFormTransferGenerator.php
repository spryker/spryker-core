<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Transfer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Generated\Shared\Transfer\ZedProductPriceTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeVariantForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\PriceForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Symfony\Component\Form\FormInterface;
use \Exception;
use \Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\PriceForm as ConcretePriceForm;

class ProductFormTransferGenerator implements ProductFormTransferGeneratorInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    protected $localeProvider;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        LocaleProvider $localeProvider
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
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
        $attributeValues = $this->generateAbstractAttributeArrayFromData($formData);
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

        $priceTransfer = $this->buildProductAbstractPriceTransfer($form);

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
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    public function buildProductConcreteTransfer(ProductAbstractTransfer $productAbstractTransfer, FormInterface $form, $idProduct)
    {
        $sku = $form->get(ProductConcreteFormEdit::FIELD_SKU)->getData();

        $productConcreteTransfer = new ZedProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($idProduct);
        $productConcreteTransfer->setAttributes([]);
        $productConcreteTransfer->setSku($sku);
        $productConcreteTransfer->setIsActive(false);
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeCode) {
            $formName = ProductFormAdd::getGeneralFormName($localeCode);
            $localeTransfer = $this->localeFacade->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createLocalizedAttributesTransfer(
                $form->get($formName),
                [],
                $localeTransfer
            );

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $priceTransfer = $this->buildProductConcretePriceTransfer($form, $productConcreteTransfer->getIdProductConcrete());
        $productConcreteTransfer->setPrice($priceTransfer);

        $stockTransfer = $this->buildProductStockTransfer($form, $productConcreteTransfer->getIdProductConcrete());
        $productConcreteTransfer->setStock($stockTransfer);

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
            ->setTaxSetId($data[ProductFormAdd::PRICE_AND_STOCK][PriceForm::FIELD_TAX_RATE]);

        return $productAbstractTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $abstractLocalizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(FormInterface $form, array $abstractLocalizedAttributes, LocaleTransfer $localeTransfer)
    {
        $abstractLocalizedAttributes = array_filter($abstractLocalizedAttributes);
        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($form->get(GeneralForm::FIELD_NAME)->getData());
        $localizedAttributesTransfer->setDescription($form->get(GeneralForm::FIELD_DESCRIPTION));
        $localizedAttributesTransfer->setAttributes($abstractLocalizedAttributes);

        if ($form->has(SeoForm::FIELD_META_TITLE)) {
            $localizedAttributesTransfer->setMetaTitle($form->get(SeoForm::FIELD_META_TITLE));
        }

        if ($form->has(SeoForm::FIELD_META_KEYWORDS)) {
            $localizedAttributesTransfer->setMetaKeywords($form->get(SeoForm::FIELD_META_KEYWORDS));
        }

        if ($form->has(SeoForm::FIELD_META_DESCRIPTION)) {
            $localizedAttributesTransfer->setMetaDescription($form->get(SeoForm::FIELD_META_DESCRIPTION));
        }

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
    public function generateAbstractAttributeArrayFromData(array $data)
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
     * @param array $data
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $attributeTransferCollection
     *
     * @return array
     */
    public function generateVariantAttributeArrayFromData(array $data, array $attributeTransferCollection)
    {
        $result = [];
        foreach ($data[ProductFormAdd::ATTRIBUTE_VARIANT] as $type => $values) {
            $attributeValues = $this->getVariantValues($values, $attributeTransferCollection[$type]);
            if ($attributeValues) {
                $result[$type] = $attributeValues;
            }
        }

        return $result;
    }

    /**
     * @param array $variantData
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     *
     * @throws \Exception
     *
     * @return array|null
     */
    protected function getVariantValues(array $variantData, ProductManagementAttributeTransfer $attributeTransfer)
    {
        $hasValue = $variantData[AttributeVariantForm::FIELD_NAME];
        $hiddenValueId = (int)$variantData[AttributeVariantForm::FIELD_VALUE_HIDDEN_ID];
        $valueIds = (array)$variantData[AttributeVariantForm::FIELD_VALUE];

        if (!$hasValue) {
            return null;
        }

        if ($hiddenValueId > 0) {
            $valueIds = [$hiddenValueId];
        }

        if (empty($valueIds)) {
            return null;
        }

        $valueEntities = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->filterByIdProductManagementAttributeValue($valueIds, Criteria::IN)
            ->find();

        $values = [];
        foreach ($valueEntities as $entity) {
            $values[$entity->getValue()] = $entity->getValue();
        }

        if (empty($values)) {
            throw new Exception('Undefined values for product management attribute: ' . $attributeTransfer->getKey());
        }

        return $values;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer
     */
    public function buildProductAbstractPriceTransfer(FormInterface $form)
    {
        $price = $form->get(ProductFormAdd::PRICE_AND_STOCK)->get(PriceForm::FIELD_PRICE)->getData();
        $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();

        $priceTransfer = (new ZedProductPriceTransfer())
            ->setIdProduct($idProductAbstract)
            ->setPrice($price);

        return $priceTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer
     */
    public function buildProductConcretePriceTransfer(FormInterface $form, $idProduct)
    {
        $price = $form->get(ProductFormAdd::PRICE_AND_STOCK)->get(PriceForm::FIELD_PRICE)->getData();

        $priceTransfer = (new ZedProductPriceTransfer())
            ->setIdProduct($idProduct)
            ->setPrice($price);

        return $priceTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function buildProductStockTransfer(FormInterface $form, $idProduct)
    {
        $stock = $form->get(ProductFormAdd::PRICE_AND_STOCK)->get(ConcretePriceForm::FIELD_STOCK)->getData();
        $sku = $form->get(ProductFormAdd::FIELD_SKU);

        $stockTransfer = (new StockProductTransfer())
            ->setIdStockProduct($idProduct)
            ->setSku($sku)
            ->setQuantity($stock);

        return $stockTransfer;
    }

}
