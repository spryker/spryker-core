<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig getConfig()
 */
class ColumnDefinitionsSubForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'field_name';

    /**
     * @var string
     */
    protected const FIELD_VISIBLE_NAME = 'field_visible_name';

    /**
     * @var string
     */
    protected const FIELD_IS_EDITABLE = 'is_editable';

    /**
     * @var string
     */
    protected const FIELD_IS_CREATABLE = 'is_creatable';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    protected const FIELD_IS_REQUIRED = 'is_required';

    /**
     * @var string
     */
    protected const FIELD_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const FIELD_EXAMPLES = 'examples';

    /**
     * @var string
     */
    protected const FIELD_ENUM_VALUES = 'enum_values';

    /**
     * @var string
     */
    protected const FIELD_MIN = 'min';

    /**
     * @var string
     */
    protected const FIELD_MAX = 'max';

    /**
     * @var string
     */
    protected const FIELD_MIN_LENGTH = 'min_length';

    /**
     * @var string
     */
    protected const FIELD_MAX_LENGTH = 'max_length';

    /**
     * @var string
     */
    protected const FIELD_SCALE = 'scale';

    /**
     * @var string
     */
    protected const FIELD_PRECISION = 'precision';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'dynamic-entity';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsEnabled($builder)
            ->addNameField($builder)
            ->addVisibleNameField($builder)
            ->addTypeField($builder)
            ->addIsCreatableField($builder)
            ->addIsEditableField($builder)
            ->addIsRequiredField($builder)
            ->addDescriptionField($builder)
            ->addExamplesField($builder)
            ->addEnumValuesField($builder)
            ->addMinField($builder)
            ->addMaxField($builder)
            ->addMinLengthField($builder)
            ->addMaxLengthField($builder)
            ->addScaleField($builder)
            ->addPrecisionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsEnabled(FormBuilderInterface $builder)
    {
        $builder->add('is_enabled', CheckboxType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => false,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVisibleNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VISIBLE_NAME, TextType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'label' => false,
            'choices' => $this->getAllowedTypes(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsCreatableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_CREATABLE, CheckboxType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsEditableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_EDITABLE, CheckboxType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsRequiredField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_REQUIRED, CheckboxType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION, TextareaType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExamplesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EXAMPLES, TextType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEnumValuesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ENUM_VALUES, TextType::class, [
            'label' => false,
            'required' => false,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMinField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MIN, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMaxField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MAX, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMinLengthField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MIN_LENGTH, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMaxLengthField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MAX_LENGTH, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPrecisionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRECISION, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addScaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SCALE, IntegerType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return array<string>
     */
    protected function getAllowedTypes(): array
    {
        return [
            'integer' => 'integer',
            'string' => 'string',
            'decimal' => 'decimal',
            'boolean' => 'boolean',
        ];
    }
}
