<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form;

use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\CmsSlotBlockCategoryGuiCommunicationFactory getFactory()
 */
class CategorySlotBlockConditionForm extends AbstractType
{
    public const OPTION_CATEGORY_ARRAY = 'option-category-array';
    public const OPTION_ALL_ARRAY = 'option-all-array';

    public const FIELD_ALL = CmsSlotBlockConditionTransfer::ALL;
    public const FIELD_CATEGORY_IDS = CmsSlotBlockConditionTransfer::CATEGORY_IDS;

    /**
     * @uses \Spryker\Shared\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY
     */
    protected const FIELD_CATEGORY = 'category';

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
            ->addAllField($builder->get(static::FIELD_CATEGORY), $options)
            ->addCategoryIdsField($builder->get(static::FIELD_CATEGORY), $options);
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
     * @param array $options
     *
     * @return $this
     */
    protected function addAllField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ALL, ChoiceType::class, [
            'required' => false,
            'choices' => $options[static::OPTION_ALL_ARRAY],
            'choice_value' => function ($choice) {
                return $choice ?? true;
            },
            'choice_attr' => function ($choice, $key, $value) {
                return [
                    'data-disable' => $value,
                    'data-inputs' => $this->getFactory()->getUtilEncoding()->encodeJson([static::FIELD_CATEGORY_IDS]),
                ];
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
        $builder->add(static::FIELD_CATEGORY_IDS, ChoiceType::class, [
            'choices' => $options[static::OPTION_CATEGORY_ARRAY],
            'required' => false,
            'multiple' => true,
            'label' => static::LABEL_CATEGORY_IDS,
        ]);

        return $this;
    }
}
