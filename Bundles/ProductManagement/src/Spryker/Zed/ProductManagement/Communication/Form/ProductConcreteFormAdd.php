<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributeCollectionType;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 */
class ProductConcreteFormAdd extends ProductConcreteFormEdit
{
    public const FIELD_SKU_AUTOGENERATE_CHECKBOX = 'sku_autogenerate_checkbox';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'form_product_concrete_super_attributes';
    public const CONTAINER_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'container_product_concrete_super_attributes';
    public const OPTION_SUPER_ATTRIBUTES = 'option_super_attributes';
    public const OPTION_ID_PRODUCT_ABSTRACT = 'option_id_product_abstract';
    public const FIELD_PRICE_SOURCE = 'price_source';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addSkuField($builder, $options)
            ->addSkuAutogenerateCheckboxField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addProductConcreteIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addPriceForm($builder, $options)
            ->addStockForm($builder, $options)
            ->addImageLocalizedForms($builder)
            ->addAssignBundledProductForm($builder, $options)
            ->addBundledProductsToBeRemoved($builder)
            ->addProductConcreteSuperAttributeForm($builder, $options)
            ->addPriceSourceCheckbox($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_ID_PRODUCT_ABSTRACT);
        $resolver->setRequired(static::OPTION_SUPER_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder, array $options = [])
    {
        $formData = $builder->getData();

        $builder->add(static::FIELD_SKU, TextType::class, [
            'label' => 'SKU',
            'constraints' => [
                new NotBlank(),
                new SkuRegex(),
                new SkuUnique($this->getFactory()->getProductFacade()),
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $formData = $event->getData();

            if (!empty($formData[static::FIELD_SKU_AUTOGENERATE_CHECKBOX])) {
                $formData[static::FIELD_SKU] = $this->getGeneratedSku($formData, $options);
                $event->setData($formData);
            }
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuAutogenerateCheckboxField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU_AUTOGENERATE_CHECKBOX, CheckboxType::class, [
            'label' => 'Autogenerate SKU',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPriceSourceCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICE_SOURCE, CheckboxType::class, [
            'label' => 'Use prices from abstract product',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductConcreteSuperAttributeForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options[static::OPTION_SUPER_ATTRIBUTES]) {
            return $this;
        }

        $builder->add(
            static::CONTAINER_PRODUCT_CONCRETE_SUPER_ATTRIBUTES,
            ProductConcreteSuperAttributeCollectionType::class,
            [
                static::OPTION_SUPER_ATTRIBUTES => $options[static::OPTION_SUPER_ATTRIBUTES],
                static::OPTION_ID_PRODUCT_ABSTRACT => $options[static::OPTION_ID_PRODUCT_ABSTRACT],
            ]
        );

        return $this;
    }

    /**
     * @param array $formData
     * @param array $options
     *
     * @return string|null
     */
    protected function getGeneratedSku(array $formData, array $options)
    {
        $idProductAbstract = $options[static::OPTION_ID_PRODUCT_ABSTRACT] ?? null;

        if ($idProductAbstract === null) {
            return null;
        }

        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById($idProductAbstract);

        $productConcreteTransfer = new ProductConcreteTransfer();
        if (isset($formData[static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES])) {
            $productConcreteTransfer->setAttributes(
                $this->getNonEmptyTransformedSubmittedSuperAttributes($formData[static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES])
            );
        }

        return $this->getFactory()->getProductFacade()->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);
    }

    /**
     * @param array $submittedAttributes
     *
     * @return array
     */
    protected function getNonEmptyTransformedSubmittedSuperAttributes(array $submittedAttributes)
    {
        return array_filter(
            $this->getFactory()->createProductConcreteSuperAttributeFilterHelper()->getTransformedSubmittedSuperAttributes($submittedAttributes),
            function ($submittedAttribute) {
                return $submittedAttribute !== null && $submittedAttribute !== '';
            }
        );
    }

    /**
     * @param array $validationGroups
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    protected function prepareDefaultsValidationGroups(array $validationGroups, FormInterface $form): array
    {
        $validationGroupsPriceSourceKey = array_search(static::VALIDATION_GROUP_PRICE_SOURCE, $validationGroups, true);

        if ($form->get(static::FIELD_PRICE_SOURCE)->getData() === true && $validationGroupsPriceSourceKey !== false) {
            unset($validationGroups[$validationGroupsPriceSourceKey]);
        }

        return $validationGroups;
    }
}
