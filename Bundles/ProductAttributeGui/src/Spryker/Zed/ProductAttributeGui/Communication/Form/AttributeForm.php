<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AttributeForm extends AbstractType
{

    const FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE = 'id_product_management_attribute';
    const FIELD_KEY = 'key';
    const FIELD_INPUT_TYPE = 'input_type';
    const FIELD_ALLOW_INPUT = 'allow_input';
    const FIELD_IS_SUPER = 'is_super';
    const FIELD_VALUES = 'values';

    const OPTION_ATTRIBUTE_TYPE_CHOICES = 'attribute_type_choices';
    const OPTION_VALUES_CHOICES = 'values_choices';
    const OPTION_IS_UPDATE = 'is_update';

    const GROUP_VALUES = 'values_group';
    const GROUP_UNIQUE_KEY = 'unique_key_group';

    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer
     */
    public function __construct(ProductAttributeGuiToProductAttributeQueryContainerInterface $productAttributeQueryContainer)
    {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
    }

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'attributeForm';
    }

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
        $builder->add(self::FIELD_ID_PRODUCT_MANAGEMENT_ATTRIBUTE, 'hidden');

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
        $builder->add(self::FIELD_KEY, new AutosuggestType(), [
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
                    'methods' => [
                        function ($key, ExecutionContextInterface $context) {
                            $keyCount = $this->productAttributeQueryContainer
                                ->queryProductAttributeKeyByKeys([$key])
                                ->count();

                            if ($keyCount > 0) {
                                $context->addViolation('Attribute key is already used');
                            }
                        },
                    ],
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
        $builder->add(self::FIELD_INPUT_TYPE, 'choice', [
            'label' => 'Input type',
            'choices' => $options[self::OPTION_ATTRIBUTE_TYPE_CHOICES],
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
        $builder->add(self::FIELD_IS_SUPER, 'checkbox', [
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
        $builder->add(self::FIELD_VALUES, new Select2ComboBoxType(), [
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
        $builder->add(self::FIELD_ALLOW_INPUT, 'checkbox', [
            'label' => 'Allow input any value other than predefined ones',
        ]);

        return $this;
    }

}
