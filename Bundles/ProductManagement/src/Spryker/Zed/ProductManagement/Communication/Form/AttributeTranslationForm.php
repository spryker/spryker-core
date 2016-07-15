<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributeTranslationForm extends AbstractType
{
    const FIELD_ATTRIBUTE_NAME = 'attribute_name';
    const FIELD_ATTRIBUTE_NAME_TRANSLATION = 'attribute_name_translation';
    const FIELD_TRANSLATE_VALUES = 'translate_values';
    const FIELD_VALUE_TRANSLATIONS = 'value_translations';

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'translation';
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
            ->addAttributeNameField($builder)
            ->addAttributeNameTranslationField($builder)
            ->addValueTranslationFields($builder)
            ->addTranslateValuesField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ATTRIBUTE_NAME, 'text', [
            'label' => 'Attribute name',
            'read_only' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeNameTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ATTRIBUTE_NAME_TRANSLATION, 'text', [
            'label' => 'Translation',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslateValuesField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TRANSLATE_VALUES, 'checkbox', [
            'label' => 'Translate predefined values',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE_TRANSLATIONS, 'collection', [
            'label' => 'Predefined value translations',
            'type' => new AttributeValueTranslationForm(),
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'options' => [],
        ]);

        return $this;
    }

}
