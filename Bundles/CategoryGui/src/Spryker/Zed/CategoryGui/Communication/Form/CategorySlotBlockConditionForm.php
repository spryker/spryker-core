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
    public const OPTION_CATEGORY_IDS = 'option-category-ids';

    public const FIELD_CATEGORY_IDS = 'categoryIds';
    protected const FIELD_ALL = 'all';

    protected const LABEL_CATEGORY_IDS = 'Categories Pages';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAllField($builder)
            ->addCategoryIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder)
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
        $builder->add(static::FIELD_CATEGORY_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_CATEGORY_IDS],
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_CATEGORY_IDS,
        ]);

        return $this;
    }
}
