<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddProductAlternativeFormType extends AbstractType
{
    protected const FIELD_PRODUCT_NAME_AUTOCOMPLETE = 'product-name-autocomplete';
    protected const FIELD_PRODUCT_SKU_AUTOCOMPLETE = 'product-sku-autocomplete';

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'product_alternative_gui';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductNameAutocompleteField($builder);
        $this->addProductSkuAutocompleteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductNameAutocompleteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_NAME_AUTOCOMPLETE, AutosuggestType::class, [
            'label' => 'Find Product by Name',
            'url' => '/product-alternative-gui/suggest-product-name',
            'attr' => [
                'placeholder' => 'Type first three letters of an existing product name for suggestions.',
            ],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductSkuAutocompleteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_SKU_AUTOCOMPLETE, AutosuggestType::class, [
            'label' => 'Find Product by SKU',
            'url' => '/product-alternative-gui/suggest-product-sku',
            'attr' => [
                'placeholder' => 'Type first three letters of an existing product SKU for suggestions.',
            ],
            'required' => false,
        ]);

        return $this;
    }
}
