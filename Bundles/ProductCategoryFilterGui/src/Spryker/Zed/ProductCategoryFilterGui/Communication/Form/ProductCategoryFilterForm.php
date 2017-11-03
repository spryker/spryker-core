<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoryFilterForm extends AbstractType
{
    const FIELD_FILTER = 'filter';

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_category';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFilterField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFilterField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FILTER, new AutosuggestType(), [
            'label' => 'Add filter',
            'url' => '/product-category-filter-gui/filter-suggestion',
            'attr' => [
                'placeholder' => 'Type first three letters of an existing filter key for suggestions.',
            ],
            'required' => false,
        ]);

        return $this;
    }
}
