<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributeForm extends AbstractType
{
    const FIELD_KEY = 'key';
    const FIELD_INPUT_TYPE = 'input_type';
    const FIELD_ALLOW_INPUT = 'allow_input';
    const FIELD_IS_MULTIPLE = 'is_multiple';
    const FIELD_VALUES = 'values';

    const OPTION_ATTRIBUTE_TYPE_CHOICES = 'attribute_type_choices';
    const OPTION_VALUES_CHOICES = 'values_choices';

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'attributeForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            self::OPTION_ATTRIBUTE_TYPE_CHOICES,
            self::OPTION_VALUES_CHOICES,
        ]);
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
            ->addKeyField($builder)
            ->addInputTypeField($builder, $options[self::OPTION_ATTRIBUTE_TYPE_CHOICES])
            ->addAllowInputField($builder)
            ->addIsMultipleField($builder)
            ->addValuesField($builder, $options[self::OPTION_VALUES_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY, new AutosuggestType(), [
            'label' => 'Attribute key',
            'url' => '/product-management/attributes/keys',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addInputTypeField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_INPUT_TYPE, 'choice', [
            'label' => 'Input type',
            'choices' => $choices,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAllowInputField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ALLOW_INPUT, 'checkbox', [
            'label' => 'Allow input any value other than predefined ones',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsMultipleField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_MULTIPLE, 'checkbox', [
            'label' => 'Allow multi select',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addValuesField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_VALUES, new Select2ComboBoxType(), [
            'label' => 'Predefined Values',
            'choices' => $choices,
            'multiple' => true,
        ]);

        $builder->get(self::FIELD_VALUES)->resetViewTransformers();

        return $this;
    }

}
