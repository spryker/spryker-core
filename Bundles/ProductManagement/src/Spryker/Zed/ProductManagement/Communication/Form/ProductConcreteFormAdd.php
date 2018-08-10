<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributesForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL = 'Super attributes';
    public const OPTION_SUPER_ATTRIBUTES = 'option_super_attributes';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSkuField($builder)
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
            ->addProductConcreteSuperAttributesForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_SUPER_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_SKU, TextType::class, [
                'label' => 'SKU',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuAutogenerateCheckboxField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_SKU_AUTOGENERATE_CHECKBOX, CheckboxType::class, [
                'label' => 'Autogenerate SKU',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductConcreteSuperAttributesForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES,
            ProductConcreteSuperAttributesForm::class,
            [
                static::OPTION_SUPER_ATTRIBUTES => $options[static::OPTION_SUPER_ATTRIBUTES],
                'label' => static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL,
            ]
        );

        return $this;
    }
}
