<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class AttributeTranslationForm extends AbstractType
{
    public const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';
    public const FIELD_KEY = 'key';
    public const FIELD_KEY_TRANSLATION = 'key_translation';
    public const FIELD_TRANSLATE_VALUES = 'translate_values';
    public const FIELD_VALUE_TRANSLATIONS = 'value_translations';

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
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY, TextType::class, [
            'label' => 'Attribute key',
            'disabled' => true,
            'attr' => [
                'read_only' => true,
            ],
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
        $builder->add(self::FIELD_KEY_TRANSLATION, TextType::class, [
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
        $builder->add(self::FIELD_TRANSLATE_VALUES, CheckboxType::class, [
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
        $builder->add(self::FIELD_VALUE_TRANSLATIONS, CollectionType::class, [
            'label' => 'Predefined value translations',
            'entry_type' => AttributeValueTranslationForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => [],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'translation';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
