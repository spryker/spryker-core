<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager;
use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeAbstractForm extends AbstractSubForm
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';
    const FIELD_VALUE_HIDDEN_ID = 'value_hidden_id';

    const LABEL = 'label';
    const MULTIPLE = 'multiple';
    const PRODUCT_SPECIFIC = 'product_specific';
    const NAME_DISABLED = 'name_disabled';
    const VALUE_DISABLED = 'value_disabled';
    const INPUT_TYPE = 'input_type';
    const ALLOW_INPUT = 'allow_input';
    const ID = 'id';

    const OPTION_ATTRIBUTE = 'option_attribute';

    const VALIDATION_GROUP_ATTRIBUTE_VALUE = 'validation_group_attribute_value';

    /**
     * @var array
     */
    protected $attributeValues;


    /**
     * @param string $name
     * @param string $attributes
     *
     * @return array
     */
    protected function getValueFieldConfig($name, $attributes)
    {
        $isDisabled = $attributes[$name][self::NAME_DISABLED];

        return [
            'read_only' => $isDisabled,
            'label' => false,
            'attr' => [
                'class' => 'attribute_metadata_value',
                'style' => '',
                'product_specific' => $attributes[$name][self::PRODUCT_SPECIFIC],
                'id_attribute' => $attributes[$name][self::ID]
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_VALUE]
                ]),
            ]
        ];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_ATTRIBUTE);

        $resolver->setDefaults([
            'validation_groups22' => function (FormInterface $form) {
                $groups = [ProductFormAdd::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT];
                $originalData = $form->getConfig()->getData();
                $submittedData = $form->getData();

                if ($submittedData[self::FIELD_NAME] && !$submittedData[self::FIELD_VALUE]) {
                    $groups[] = self::VALIDATION_GROUP_ATTRIBUTE_VALUE;
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
        parent::buildForm($builder, $options);

        $this
            ->addCheckboxNameField($builder, $options)
            ->addValueField($builder, $options)
            ->addValueIdHiddenField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCheckboxNameField(FormBuilderInterface $builder, array $options)
    {
        $attributes = $options[AttributeAbstractForm::OPTION_ATTRIBUTE];

        $name = $builder->getName();
        $label = $attributes[$name][self::LABEL];
        $isDisabled = $attributes[$name][self::NAME_DISABLED];
        $isProductSpecific = $attributes[$name][self::PRODUCT_SPECIFIC];

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $label,
                'read_only' => $isDisabled,
                'disabled' => $isDisabled,
                'attr' => [
                    'class' => 'attribute_metadata_checkbox',
                    'product_specific' => $isProductSpecific,
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
    protected function addValueIdHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_VALUE_HIDDEN_ID, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $name = $builder->getName();
        $attributes = $options[self::OPTION_ATTRIBUTE];

        $inputManager = new AttributeInputManager();
        $inputType = $attributes[$name][self::INPUT_TYPE];
        $allowInput = $attributes[$name][self::ALLOW_INPUT];
        $isMultiple = $attributes[$name][self::MULTIPLE];
        $isDisabled = $attributes[$name][self::NAME_DISABLED];

        $input = $inputManager->getSymfonyInputType($inputType);
        $config = $this->getValueFieldConfig($name, $attributes);
        $config['attr']['style'] .= ' width: 250px';
        $config['attr']['data-value'] = null;

        if (strtolower($input) === 'select2') {
            $input = new Select2ComboBoxType();
        }

        if ($isMultiple || !$allowInput) {
            $input = new Select2ComboBoxType();

            $config['multiple'] = $isMultiple;
            $config['attr']['style'] .= ' width: 250px';
            $config['choices'] = [];
            $config['attr']['class'] .= ' ajax';

            if ($allowInput) {
                $config['attr']['tags'] = true;
            }
        } else {
            $config['attr']['class'] .= ' kv_attribute_autocomplete';
        }

        if ($isDisabled) {
            $config = $this->getValueFieldConfig($name, $attributes);
            $config['read_only'] = true;
            $input = $inputManager->getSymfonyInputType(null);
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

}
