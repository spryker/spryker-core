<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;

class ProductFormTransferMapper implements ProductFormTransferMapperInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_PRODUCTS
     *
     * @var string
     */
    protected const ADD_PRODUCT_CONCRETE_FORM_FIELD_PRODUCTS = 'products';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const ADD_PRODUCT_CONCRETE_FORM_FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteSuperAttributeForm::FIELD_SKU
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteSuperAttributeForm::FIELD_NAME
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteSuperAttributeForm::FIELD_SUPER_ATTRIBUTES
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\SuperAttributeForm::FIELD_VALUE
     *
     * @var string
     */
    protected const SUPER_ATTRIBUTE_FORM_FIELD_VALUE = 'value';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\SuperAttributeForm::FIELD_ATTRIBUTE
     *
     * @var string
     */
    protected const SUPER_ATTRIBUTE_FORM_FIELD_ATTRIBUTE = 'attribute';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAttributeValueForm::FIELD_VALUE
     *
     * @var string
     */
    protected const PRODUCT_ATTRIBUTE_VALUE_FORM_FIELD_VALUE = 'value';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param array<mixed> $addProductConcreteFormData
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapAddProductConcreteFormDataToProductConcreteCollectionTransfer(
        array $addProductConcreteFormData,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        $idProductAbstract = (int)$addProductConcreteFormData[static::ADD_PRODUCT_CONCRETE_FORM_FIELD_ID_PRODUCT_ABSTRACT];
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $defaultLocaleTransfer = $this->localeFacade->getCurrentLocale();

        foreach ($addProductConcreteFormData[static::ADD_PRODUCT_CONCRETE_FORM_FIELD_PRODUCTS] as $productConcreteFormData) {
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->setFkProductAbstract($idProductAbstract)
                ->setSku($productConcreteFormData[static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU])
                ->setIsActive(false);
            $productConcreteTransfer = $this->addLocalizedAttributesToProductConcrete(
                $productConcreteTransfer,
                $defaultLocaleTransfer,
                $localeTransfers,
                $productConcreteFormData,
            );
            $productConcreteTransfer = $this->addAttributesToProductConcrete($productConcreteTransfer, $productConcreteFormData);

            $productConcreteCollectionTransfer->addProduct($productConcreteTransfer);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $defaultLocaleTransfer
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     * @param array<mixed> $productConcreteFormData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function addLocalizedAttributesToProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $defaultLocaleTransfer,
        array $localeTransfers,
        array $productConcreteFormData
    ): ProductConcreteTransfer {
        foreach ($localeTransfers as $localeTransfer) {
            $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setLocale($localeTransfer);
            $localizedAttributesTransfer->setName(
                $localeTransfer->getIdLocaleOrFail() === $defaultLocaleTransfer->getIdLocaleOrFail()
                    ? $productConcreteFormData[static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_NAME]
                    : '',
            );

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<mixed> $productConcreteFormData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function addAttributesToProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        array $productConcreteFormData
    ): ProductConcreteTransfer {
        $attributes = [];
        foreach ($productConcreteFormData[static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SUPER_ATTRIBUTES] as $superAttributeFormData) {
            $productAttributeValueFormData = $superAttributeFormData[static::SUPER_ATTRIBUTE_FORM_FIELD_ATTRIBUTE];
            $attributes[$superAttributeFormData[static::SUPER_ATTRIBUTE_FORM_FIELD_VALUE]] = $productAttributeValueFormData[static::PRODUCT_ATTRIBUTE_VALUE_FORM_FIELD_VALUE];
        }

        $productConcreteTransfer->setAttributes($attributes);

        return $productConcreteTransfer;
    }
}
