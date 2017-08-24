<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Attribute;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeTranslationForm extends AbstractType
{

    const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';
    const FIELD_KEY = 'key';
    const FIELD_KEY_TRANSLATION = 'key_translation';
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
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
        ]);

        $resolver->setDefaults([
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                $submittedData = $form->getData();

                if (isset($submittedData[self::FIELD_TRANSLATE_VALUES]) && $submittedData[self::FIELD_TRANSLATE_VALUES]) {
                    return [Constraint::DEFAULT_GROUP, AttributeValueTranslationForm::GROUP_VALUE_TRANSLATIONS];
                }

                return [Constraint::DEFAULT_GROUP];
            },
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
            ->addIdProductManagementAttributeField($builder)
            ->addAttributeKeyField($builder)
            ->addAttributeKeyTranslationField($builder)
            ->addTranslateValuesField($builder)
            ->addValueTranslationFields($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductManagementAttributeField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY, 'text', [
            'label' => 'Attribute key',
            'read_only' => true,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeKeyTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY_TRANSLATION, 'text', [
            'label' => 'Translation',
            'constraints' => [
                new NotBlank(),
            ],
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
