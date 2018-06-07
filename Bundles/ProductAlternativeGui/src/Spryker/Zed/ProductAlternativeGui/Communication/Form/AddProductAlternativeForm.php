<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class AddProductAlternativeForm extends AbstractType
{
    protected const FIELD_PRODUCT_NAME_OR_SKU_AUTOCOMPLETE = 'searchtext-autocomplete';
    protected const FIELD_PRODUCT_ALTERNATIVES = 'product_alternatives';

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
        $this
            ->addProductNameOrSkuAutocompleteField($builder)
            ->addProductAlternativesField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductNameOrSkuAutocompleteField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_PRODUCT_NAME_OR_SKU_AUTOCOMPLETE, AutosuggestType::class, [
            'label' => 'Add Product Alternative by Name or SKU',
            'url' => '/product-alternative-gui/suggest',
            'attr' => [
                'placeholder' => 'Type three letters of name or sku for suggestions.',
            ],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\ProductAlternativeGui\Communication\Form\AddProductAlternativeForm
     */
    protected function addProductAlternativesField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_PRODUCT_ALTERNATIVES, CollectionType::class, [
            'entry_type' => ProductAlternativeCollectionForm::class,
            'entry_options' => [],
            'label' => false,
            'attr' => [
                'class' => 'hidden',
            ],
            'required' => false,
        ]);

        return $this;
    }
}
