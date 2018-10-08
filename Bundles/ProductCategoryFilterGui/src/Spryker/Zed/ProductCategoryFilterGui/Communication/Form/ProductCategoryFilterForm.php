<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductCategoryFilterForm extends AbstractType
{
    public const FIELD_FILTER_AUTOCOMPLETE = 'filter-autocomplete';
    public const FIELD_FILTERS = 'filters';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'product_category_filter';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFilterAutocompleteField($builder);
        $this->addActiveFilterHiddenField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFilterAutocompleteField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FILTER_AUTOCOMPLETE, AutosuggestType::class, [
            'label' => 'Add filter',
            'url' => '/product-category-filter-gui/filter-suggestion',
            'attr' => [
                'placeholder' => 'Type first three letters of an existing filter key for suggestions.',
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
    protected function addActiveFilterHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FILTERS, HiddenType::class);

        return $this;
    }
}
