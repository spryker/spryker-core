<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class CategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_CATEGORY_ARRAY = 'option-category-array';

    protected const FIELD_ALL = 'all';
    protected const FIELD_CATEGORY_IDS = 'ids';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAllField($builder);
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
                'All' => true,
                'Not all' => false,
            ],
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
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
        $builder->add(static::FIELD_CATEGORY_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_CATEGORY_ARRAY],
            'required' => false,
            'multiple' => true,
            'label' => false,
        ]);
    }
}
