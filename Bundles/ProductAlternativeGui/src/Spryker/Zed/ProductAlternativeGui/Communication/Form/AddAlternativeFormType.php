<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddAlternativeFormType extends AbstractType
{
    protected const FIELD_PRODUCT_NAME_OR_SKU_AUTOCOMPLETE = 'searchtext-autocomplete';

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
        $this->addProductNameOrSkuAutocompleteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductNameOrSkuAutocompleteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_NAME_OR_SKU_AUTOCOMPLETE, AutosuggestType::class, [
            'label' => 'Find Product by Name or SKU',
            'url' => '/product-alternative-gui/suggest',
            'attr' => [
                'placeholder' => 'Type first three letters of an existing product name or sku for suggestions.',
            ],
            'required' => false,
        ]);

        return $this;
    }
}
