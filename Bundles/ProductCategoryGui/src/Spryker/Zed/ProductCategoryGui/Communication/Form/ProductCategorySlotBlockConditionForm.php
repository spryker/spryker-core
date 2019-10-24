<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductCategoryGui\Communication\ProductCategoryGuiCommunicationFactory getFactory()
 */
class ProductCategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_PRODUCT_ARRAY = 'option-product-array';
    public const OPTION_CATEGORY_ARRAY = 'option-category-array';

    public const FIELD_ALL = 'all';
    protected const FIELD_PRODUCT_IDS = 'productIds';
    protected const FIELD_CATEGORY_IDS = 'categoryIds';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAllField($builder);
        $this->addProductIdsField($builder, $options);
        $this->addCategoryIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addAllField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => [
                'All Product Pages' => true,
                'Specific Product Pages' => false,
            ],
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
            'label' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addProductIdsField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_PRODUCT_IDS, Select2ComboBoxType::class, [
            'label' => 'Products Pages',
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_PRODUCT_ARRAY],
            'attr' => [
                'data-autocomplete-url' => '',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addCategoryIdsField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_CATEGORY_IDS, Select2ComboBoxType::class, [
            'label' => 'Product pages per Category',
            'choices' => $options[static::OPTION_CATEGORY_ARRAY],
            'required' => false,
            'multiple' => true,
        ]);
    }
}
