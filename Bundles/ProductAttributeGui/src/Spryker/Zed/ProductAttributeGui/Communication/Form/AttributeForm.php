<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class AttributeForm extends AbstractType
{
    public const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';
    public const FIELD_KEY = 'key';
    public const FIELD_INPUT_TYPE = 'input_type';
    public const FIELD_ALLOW_INPUT = 'allow_input';
    public const FIELD_IS_SUPER = 'is_super';
    public const FIELD_VALUES = 'values';

    public const OPTION_ATTRIBUTE_TYPE_CHOICES = 'attribute_type_choices';
    public const OPTION_VALUES_CHOICES = 'values_choices';
    public const OPTION_IS_UPDATE = 'is_update';

    public const GROUP_VALUES = 'values_group';
    public const GROUP_UNIQUE_KEY = 'unique_key_group';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            self::OPTION_ATTRIBUTE_TYPE_CHOICES,
            self::OPTION_VALUES_CHOICES,
        ]);

        $resolver->setDefaults([
            self::OPTION_IS_UPDATE => false,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                $groups = [Constraint::DEFAULT_GROUP];
                $originalData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if (!isset($submittedData[self::FIELD_ALLOW_INPUT]) || !$submittedData[self::FIELD_ALLOW_INPUT] ||
                    (isset($submittedData[self::FIELD_IS_SUPER]) && $submittedData[self::FIELD_IS_SUPER])
                ) {
                    $groups[] = self::GROUP_VALUES;
                }

                if (!isset($originalData[self::FIELD_KEY]) || $submittedData[self::FIELD_KEY] !== $originalData[self::FIELD_KEY]) {
                    $groups[] = self::GROUP_UNIQUE_KEY;
                }

                return $groups;
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
            ->addIdProductManagementAttribute($builder)
            ->addKeyField($builder, $options)
            ->addInputTypeField($builder, $options)
            ->addIsSuperField($builder, $options)
            ->addValuesField($builder, $options)
            ->addAllowInputField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductManagementAttribute(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_KEY, AutosuggestType::class, [
            'label' => 'Attribute key',
            'url' => '/product-attribute-gui/attribute/keys',
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^[a-z\-0-9_:]+$/',
                    'message' => 'This field contains illegal characters. It should contain only lower case letters, ' .
                        'digits, numbers, underscores ("_"), hyphens ("-") and colons (":").',
                ]),
                new Callback([
                    'callback' => function ($key, ExecutionContextInterface $context) {
                        $keyCount = $this->getFactory()->getProductAttributeQueryContainer()
                            ->queryProductAttributeKeyByKeys([$key])
                            ->count();

                        if ($keyCount > 0) {
                            $context->addViolation('Attribute key is already used');
                        }
                    },
                    'groups' => [self::GROUP_UNIQUE_KEY],
                ]),
            ],
            'disabled' => $options[self::OPTION_IS_UPDATE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addInputTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_INPUT_TYPE, ChoiceType::class, [
            'label' => 'Input type',
            'choices' => array_flip($options[self::OPTION_ATTRIBUTE_TYPE_CHOICES]),
            'constraints' => [
                new NotBlank(),
            ],
            'disabled' => $options[self::OPTION_IS_UPDATE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIsSuperField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_IS_SUPER, CheckboxType::class, [
            'label' => 'Super attribute',
            'disabled' => $options[self::OPTION_IS_UPDATE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addValuesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_VALUES, Select2ComboBoxType::class, [
            'label' => 'Predefined Values',
            'choices' => $options[self::OPTION_VALUES_CHOICES],
            'multiple' => true,
            'constraints' => [
                new NotBlank([
                    'groups' => self::GROUP_VALUES,
                ]),
            ],
        ]);

        $builder->get(self::FIELD_VALUES)->resetViewTransformers();

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAllowInputField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ALLOW_INPUT, CheckboxType::class, [
            'label' => 'Allow input any value other than predefined ones',
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'attributeForm';
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
