<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListProductConcreteRelationType extends AbstractType
{
    const FIELD_PRODUCTS = ProductListProductConcreteRelationTransfer::PRODUCT_IDS;
    const OPTION_PRODUCT_NAMES = 'option-product-names';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'products';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_PRODUCT_NAMES);

        $resolver->setDefaults([
            'data_class' => ProductListProductConcreteRelationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductsField(
            $builder,
            $options[static::OPTION_PRODUCT_NAMES]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $categoryList
     *
     * @return void
     */
    protected function addProductsField(FormBuilderInterface $builder, array $categoryList)
    {
        $builder->add(static::FIELD_PRODUCTS, Select2ComboBoxType::class, [
            'property_path' => static::FIELD_PRODUCTS,
            'label' => 'Products',
            'choices' => $categoryList,
            'choices_as_values' => true,
            'multiple' => true,
            'required' => false,
        ]);
    }
}
