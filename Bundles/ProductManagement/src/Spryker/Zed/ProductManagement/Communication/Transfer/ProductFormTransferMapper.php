<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\Product\AttributeSuperForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ConcreteGeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\StockForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\GeneralForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Product\SeoForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Symfony\Component\Form\FormInterface;

class ProductFormTransferMapper implements ProductFormTransferMapperInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

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
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface>
     */
    protected $productFormTransferMapperExpanderPlugins;

    /**
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractTransferMapperPluginInterface>
     */
    protected $productAbstractTransferMapperPlugins;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface
     */
    protected $productConcreteSuperAttributeFilterHelperInterface;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider $localeProvider
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface> $productFormTransferMapperExpanderPlugins
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractTransferMapperPluginInterface> $productAbstractTransferMapperPlugins
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface $productConcreteSuperAttributeFilterHelperInterface
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        LocaleProvider $localeProvider,
        array $productFormTransferMapperExpanderPlugins,
        array $productAbstractTransferMapperPlugins,
        ProductConcreteSuperAttributeFilterHelperInterface $productConcreteSuperAttributeFilterHelperInterface
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->localeProvider = $localeProvider;
        $this->productFormTransferMapperExpanderPlugins = $productFormTransferMapperExpanderPlugins;
        $this->productAbstractTransferMapperPlugins = $productAbstractTransferMapperPlugins;
        $this->productConcreteSuperAttributeFilterHelperInterface = $productConcreteSuperAttributeFilterHelperInterface;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int|null $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function buildProductAbstractTransfer(FormInterface $form, $idProductAbstract)
    {
        $formData = $form->getData();
        $localeCollection = $this->localeProvider->getLocaleCollection();

        $productAbstractTransfer = $this->createProductAbstractTransfer($formData);

        $attributes = $this->getAbstractAttributes($idProductAbstract);
        $productAbstractTransfer->setAttributes($attributes);

        $localizedData = $this->generateLocalizedData($localeCollection, $formData);

        foreach ($localizedData as $localeCode => $data) {
            $localeTransfer = $this->localeFacade->getLocale($localeCode);

            $localizedAttributesTransfer = $this->createAbstractLocalizedAttributesTransfer($form, $localeTransfer, $idProductAbstract);

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $imageSetCollection = $this->buildProductImageSetCollection($form);
        $productAbstractTransfer->setImageSets(new ArrayObject($imageSetCollection));
        $productAbstractTransfer->setStoreRelation($formData[ProductFormAdd::FORM_STORE_RELATION]);

        $priceProducts = $this->updatePricesDimension($formData);
        $productAbstractTransfer->setPrices($priceProducts);

        return $productAbstractTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeCollection
     * @param array $formData
     *
     * @return array
     */
    protected function generateLocalizedData(array $localeCollection, array $formData)
    {
        $localizedData = [];
        foreach ($localeCollection as $localeTransfer) {
            $generalFormName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
            $seoFormName = ProductFormAdd::getSeoFormName($localeTransfer->getLocaleName());

            $localizedData[$localeTransfer->getLocaleName()] = array_merge($formData[$generalFormName], $formData[$seoFormName]);
        }

        return $localizedData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int|null $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function buildProductConcreteTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        FormInterface $form,
        $idProduct = null
    ) {
        $sku = $form->get(ProductConcreteFormEdit::FIELD_SKU)->getData();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($idProduct)
            ->setAttributes($this->getConcreteAttributes($form->getData(), $idProduct))
            ->setSku($sku)
            ->setAbstractSku($productAbstractTransfer->getSku())
            ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());

        if ($idProduct === null) {
            $productConcreteTransfer->setIsActive(false);
        }

        $productConcreteTransfer = $this->assignProductToBeBundled($form, $productConcreteTransfer);
        $productConcreteTransfer = $this->assignProductsToBeRemovedFromBundle($form, $productConcreteTransfer);

        $localeCollection = $this->localeProvider->getLocaleCollection();
        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());

            $localizedAttributesTransfer = $this->createConcreteLocalizedAttributesTransfer($form->get($formName), $localeTransfer, $idProduct);

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        $formData = $form->getData();
        $priceProducts = $this->updatePricesDimension($formData);
        $productConcreteTransfer->setPrices($priceProducts);

        $stockCollection = $this->buildProductStockCollectionTransfer($form);
        $productConcreteTransfer->setStocks(new ArrayObject($stockCollection));

        $imageSetCollection = $this->buildProductImageSetCollection($form);
        $productConcreteTransfer->setImageSets(new ArrayObject($imageSetCollection));

        $productConcreteTransfer->setValidFrom($formData[ProductConcreteFormEdit::FIELD_VALID_FROM]);
        $productConcreteTransfer->setValidTo($formData[ProductConcreteFormEdit::FIELD_VALID_TO]);

        if (!empty($formData[ProductConcreteFormAdd::FIELD_PRICE_SOURCE])) {
            $this->setAbstractProductPricesToConcreteProduct($productConcreteTransfer, $productAbstractTransfer);
        }

        foreach ($this->productFormTransferMapperExpanderPlugins as $plugin) {
            $productConcreteTransfer = $plugin->map($productConcreteTransfer, $formData);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param array $formData
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function updatePricesDimension(array $formData): ArrayObject
    {
        $priceProducts = $formData[ProductFormAdd::FIELD_PRICES];

        if (!$formData[ProductFormAdd::FORM_PRICE_DIMENSION]) {
            return $priceProducts;
        }

        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->fromArray($formData[ProductFormAdd::FORM_PRICE_DIMENSION], true);

        if (!$priceProductDimensionTransfer->getType()) {
            return $priceProducts;
        }

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        foreach ($priceProducts as $priceProductTransfer) {
            $priceDimension = $priceProductTransfer->getPriceDimension();

            if (!$priceDimension) {
                $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);

                continue;
            }

            $priceProductTransfer->setPriceDimension(
                $priceDimension->fromArray($priceProductDimensionTransfer->modifiedToArray()),
            );
        }

        return $priceProducts;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(array $data)
    {
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->fromArray($data, true)
            ->setIdProductAbstract($data[ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT])
            ->setSku($data[ProductFormAdd::FIELD_SKU])
            ->setIdTaxSet($data[ProductFormAdd::FIELD_TAX_RATE]);

        foreach ($this->productAbstractTransferMapperPlugins as $productAbstractTransferMapperPlugin) {
            $productAbstractTransfer = $productAbstractTransferMapperPlugin->map($data, $productAbstractTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $formObject
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int|null $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createAbstractLocalizedAttributesTransfer(
        FormInterface $formObject,
        LocaleTransfer $localeTransfer,
        $idProductAbstract
    ) {
        $formName = ProductFormAdd::getGeneralFormName($localeTransfer->getLocaleName());
        $form = $formObject->get($formName);

        $attributes = $this->getAbstractLocalizedAttributes($idProductAbstract, $localeTransfer->getIdLocale());

        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($form->get(GeneralForm::FIELD_NAME)->getData());
        $localizedAttributesTransfer->setDescription($form->get(GeneralForm::FIELD_DESCRIPTION)->getData());
        $localizedAttributesTransfer->setAttributes($attributes);

        $formName = ProductFormAdd::getSeoFormName($localeTransfer->getLocaleName());
        if ($formObject->has($formName)) {
            $form = $formObject->get($formName);

            $localizedAttributesTransfer->setMetaTitle($form->get(SeoForm::FIELD_META_TITLE)->getData());
            $localizedAttributesTransfer->setMetaKeywords($form->get(SeoForm::FIELD_META_KEYWORDS)->getData());
            $localizedAttributesTransfer->setMetaDescription($form->get(SeoForm::FIELD_META_DESCRIPTION)->getData());
        }

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createConcreteLocalizedAttributesTransfer(FormInterface $form, LocaleTransfer $localeTransfer, $idProduct)
    {
        $localizedAttributes = $this->getConcreteLocalizedAttributes($idProduct, $localeTransfer->getIdLocale());

        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setLocale($localeTransfer);
        $localizedAttributesTransfer->setName($form->get(ConcreteGeneralForm::FIELD_NAME)->getData());
        $localizedAttributesTransfer->setDescription($form->get(ConcreteGeneralForm::FIELD_DESCRIPTION)->getData());
        $localizedAttributesTransfer->setIsSearchable($form->get(ConcreteGeneralForm::FIELD_IS_SEARCHABLE)->getData());
        $localizedAttributesTransfer->setAttributes($localizedAttributes);

        return $localizedAttributesTransfer;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    public function generateAbstractAttributeArrayFromData(array $data)
    {
        $attributes = [];
        $localeCollection = $this->localeProvider->getLocaleCollection(true);

        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getAbstractAttributeFormName($localeTransfer->getLocaleName());
            foreach ($data[$formName] as $type => $values) {
                $attributes[$localeTransfer->getLocaleName()][$type] = $values['value'];
            }
        }

        return $attributes;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $attributeTransferCollection
     *
     * @return array
     */
    public function generateVariantAttributeArrayFromData(array $data, array $attributeTransferCollection)
    {
        $result = [];
        foreach ($data[ProductFormAdd::FORM_ATTRIBUTE_SUPER] as $type => $values) {
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
     * @return array|null
     */
    protected function getVariantValues(array $variantData, ProductManagementAttributeTransfer $attributeTransfer)
    {
        $hasValue = $variantData[AttributeSuperForm::FIELD_NAME];
        $values = (array)$variantData[AttributeSuperForm::FIELD_VALUE];

        if (!$hasValue) {
            return null;
        }

        return $values;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function buildProductImageSetCollection(FormInterface $form)
    {
        $transferCollection = [];
        $localeCollection = $this->localeProvider->getLocaleCollection(true);

        foreach ($localeCollection as $localeTransfer) {
            $formName = ProductFormAdd::getImagesFormName($localeTransfer->getLocaleName());

            $imageSetCollection = $form->get($formName);
            foreach ($imageSetCollection as $imageSet) {
                $imageSetData = array_filter($imageSet->getData());

                $imageSetTransfer = (new ProductImageSetTransfer())->fromArray($imageSetData, true);

                if ($this->localeFacade->hasLocale($localeTransfer->getLocaleName())) {
                    $imageSetTransfer->setLocale($localeTransfer);
                }

                $productImages = $this->buildProductImageCollection($imageSet->get(ImageSetForm::PRODUCT_IMAGES)
                    ->getData());
                $object = new ArrayObject($productImages);
                $imageSetTransfer->setProductImages($object);

                $transferCollection[] = $imageSetTransfer;
            }
        }

        return $transferCollection;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    public function buildProductImageCollection(array $data)
    {
        $result = [];

        foreach ($data as $imageData) {
            $imageTransfer = new ProductImageTransfer();
            $imageData[ImageCollectionForm::FIELD_SORT_ORDER] = (int)$imageData[ImageCollectionForm::FIELD_SORT_ORDER];
            $imageTransfer->fromArray($imageData, true);

            $result[] = $imageTransfer;
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array<\Generated\Shared\Transfer\StockProductTransfer>
     */
    public function buildProductStockCollectionTransfer(FormInterface $form)
    {
        $result = [];
        $sku = $form->get(ProductFormAdd::FIELD_SKU)->getData();

        if (!$form->has(ProductFormAdd::FORM_PRICE_AND_STOCK)) {
            return $result;
        }
        foreach ($form->get(ProductFormAdd::FORM_PRICE_AND_STOCK) as $stockForm) {
            $stockData = $stockForm->getData();
            $type = $stockForm->get(StockForm::FIELD_TYPE)->getData();
            $quantity = $stockForm->get(StockForm::FIELD_QUANTITY)->getData();
            $isNeverOutOfStock = $stockForm->get(StockForm::FIELD_IS_NEVER_OUT_OF_STOCK)->getData();

            $stockTransfer = (new StockProductTransfer())->fromArray($stockData, true)
                ->setSku($sku)
                ->setQuantity($quantity)
                ->setStockType($type)
                ->setIsNeverOutOfStock($isNeverOutOfStock);

            $result[] = $stockTransfer;
        }

        return $result;
    }

    /**
     * @param int|null $idProductAbstract
     *
     * @return array
     */
    protected function getAbstractAttributes($idProductAbstract)
    {
        $attributes = [];

        $entity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if ($entity) {
            $attributes = json_decode($entity->getAttributes(), true);
        }

        return $attributes;
    }

    /**
     * @param int|null $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    protected function getAbstractLocalizedAttributes($idProductAbstract, $idLocale)
    {
        $attributes = [];

        $entity = $this->productQueryContainer
            ->queryProductAbstractLocalizedAttributes($idProductAbstract)
            ->filterByFkLocale($idLocale)
            ->findOne();

        if ($entity) {
            $attributes = json_decode($entity->getAttributes(), true);
        }

        return $attributes;
    }

    /**
     * @param array $formData
     * @param int|null $idProduct
     *
     * @return array
     */
    protected function getConcreteAttributes(array $formData, ?int $idProduct): array
    {
        if (
            $idProduct === null &&
            isset($formData[ProductConcreteFormAdd::CONTAINER_PRODUCT_CONCRETE_SUPER_ATTRIBUTES][ProductConcreteFormAdd::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES])
        ) {
            return $this
                ->productConcreteSuperAttributeFilterHelperInterface
                ->getTransformedSubmittedSuperAttributes(
                    $formData[ProductConcreteFormAdd::CONTAINER_PRODUCT_CONCRETE_SUPER_ATTRIBUTES][ProductConcreteFormAdd::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES],
                );
        }

        $attributes = [];

        $entity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->findOne();

        if ($entity) {
            $attributes = json_decode($entity->getAttributes(), true);
        }

        return $attributes;
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    protected function getConcreteLocalizedAttributes($idProduct, $idLocale)
    {
        $attributes = [];

        $entity = $this->productQueryContainer
            ->queryProductLocalizedAttributes($idProduct)
            ->filterByFkLocale($idLocale)
            ->findOne();

        if ($entity) {
            $attributes = json_decode($entity->getAttributes(), true);
        }

        return $attributes;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function assignProductToBeBundled(FormInterface $form, ProductConcreteTransfer $productConcreteTransfer)
    {
        if (!isset($form->getData()[ProductConcreteFormEdit::FORM_ASSIGNED_BUNDLED_PRODUCTS])) {
            return $productConcreteTransfer;
        }

        $productsToBeBundled = new ArrayObject();
        $productBundleTransfer = new ProductBundleTransfer();
        foreach ($form->getData()[ProductConcreteFormEdit::FORM_ASSIGNED_BUNDLED_PRODUCTS] as $bundledProduct) {
            $productForBundleTransfer = new ProductForBundleTransfer();
            $productForBundleTransfer->fromArray($bundledProduct, true);
            $productsToBeBundled->append($productForBundleTransfer);
        }

        $productBundleTransfer->setBundledProducts($productsToBeBundled);

        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function assignProductsToBeRemovedFromBundle(
        FormInterface $form,
        ProductConcreteTransfer $productConcreteTransfer
    ) {
        if (!isset($form->getData()[ProductConcreteFormEdit::BUNDLED_PRODUCTS_TO_BE_REMOVED]) || !$form->getData()[ProductConcreteFormEdit::BUNDLED_PRODUCTS_TO_BE_REMOVED]) {
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->getProductBundle()
            ->setBundlesToRemove($form->getData()[ProductConcreteFormEdit::BUNDLED_PRODUCTS_TO_BE_REMOVED]);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function setAbstractProductPricesToConcreteProduct(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): void {
        $abstractProductPriceProductTransfers = $productAbstractTransfer->getPrices();
        $concreteProductPriceProductTransfers = new ArrayObject();

        foreach ($abstractProductPriceProductTransfers as $abstractProductPriceProductTransfer) {
            $concreteProductPriceProductTransfer = $this->createPriceProductTransfer(
                $productConcreteTransfer,
                $abstractProductPriceProductTransfer,
            );

            $concreteProductPriceProductTransfers->append($concreteProductPriceProductTransfer);
        }

        $productConcreteTransfer->setPrices($concreteProductPriceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $abstractProductPriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function createPriceDimensionTransfer(
        PriceProductTransfer $abstractProductPriceProductTransfer
    ): PriceProductDimensionTransfer {
        return (new PriceProductDimensionTransfer())
            ->setType($abstractProductPriceProductTransfer->getPriceDimension()->getType());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $abstractProductPriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        PriceProductTransfer $abstractProductPriceProductTransfer
    ): PriceProductTransfer {
        return (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($abstractProductPriceProductTransfer->getPriceTypeName())
            ->setFkPriceType($abstractProductPriceProductTransfer->getFkPriceType())
            ->setPriceType($abstractProductPriceProductTransfer->getPriceType())
            ->setPriceDimension($this->createPriceDimensionTransfer($abstractProductPriceProductTransfer))
            ->setMoneyValue($abstractProductPriceProductTransfer->getMoneyValue());
    }
}
