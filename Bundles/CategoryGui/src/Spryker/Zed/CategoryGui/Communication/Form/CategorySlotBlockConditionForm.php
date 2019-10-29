<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class CategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_CATEGORY_IDS = 'option-category-ids';

    protected const FIELD_CATEGORY = 'category';
    protected const FIELD_CATEGORY_IDS = 'categoryIds';
    protected const FIELD_ALL = 'all';

    protected const LABEL_CATEGORY_IDS = 'Category Pages';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCategoryField($builder)
            ->addAllField($builder)
            ->addCategoryIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addCategoryField($builder)
    {
        $builder->add(static::FIELD_CATEGORY, FormType::class, [
            'label' => false,
            'error_mapping' => [
                '.' => static::FIELD_CATEGORY_IDS,
            ],
            'constraints' => [
                $this->getFactory()->createCategoryConditionsConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder)
    {
        $builder->get(static::FIELD_CATEGORY)->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => [
                'All Category Pages' => true,
                'Specific Category Pages' => false,
            ],
            'choice_value' => function ($choice) {
                return $choice ?? true;
            },
            'expanded' => true,
            'multiple' => false,
            'placeholder' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCategoryIdsField(FormBuilderInterface $builder, array $options)
    {
        $builder->get(static::FIELD_CATEGORY)->add(static::FIELD_CATEGORY_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_CATEGORY_IDS],
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_CATEGORY_IDS,
        ]);

        return $this;
    }
}
