<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormAttributeAbstract extends AbstractSubForm
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    const LABEL = 'label';
    const MULTIPLE = 'multiple';
    const PRODUCT_SPECIFIC = 'product_specific';
    const NAME_DISABLED = 'name_disabled';
    const VALUE_DISABLED = 'value_disabled';
    const INPUT = 'input';
    const ALLOW_INPUT = 'allow_input';
    const ID = 'id';

    const OPTION_ATTRIBUTE = 'option_attribute';

    /**
     * @var array
     */
    protected $attributeValues;


    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_ATTRIBUTE);
        $resolver->setRequired(ProductFormAdd::SUB_FORM_NAME);
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
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCheckboxNameField(FormBuilderInterface $builder, array $options)
    {
        $attributes = $options[ProductFormAttributeAbstract::OPTION_ATTRIBUTE];

        $name = $builder->getName();
        $label = $name;
        $isDisabled = true;

        if (isset($attributes[$name])) {
            $label = $attributes[$name][self::LABEL];
            $isDisabled = $attributes[$name][self::NAME_DISABLED];
        }

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $label,
                'disabled' => $isDisabled,
                'attr' => [
                    'class' => 'attribute_metadata_checkbox',
                    'product_specific' => $attributes[$name][self::PRODUCT_SPECIFIC],
                ],
            ]);

        return $this;
    }

    /**
     * @param string $name
     * @param string $attributes
     *
     * @return array
     */
    protected function getValueFieldConfig($name, $attributes)
    {
        $isDisabled = $attributes[$name][self::VALUE_DISABLED];

        return [
            'disabled' => $isDisabled,
            'label' => false,
            'attr' => [
                'class' => 'attribute_metadata_value',
                'style' => '',
                'product_specific' => $attributes[$name][self::PRODUCT_SPECIFIC],
                'id_attribute' => $attributes[$name][self::ID]
            ],
            'constraints' => [
                new Callback([
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            //TODO more sophisticated validation
                            if (!($dataToValidate)) {
                                //$context->addViolation('Please enter attribute value.');
                            }
                        },
                    ],
                ]),
            ]
            /*
           'constraints' => [
                new AttributeFieldNotBlank([
                    'attributeFieldValue' => self::FIELD_VALUE,
                    'attributeCheckboxFieldName' => self::FIELD_NAME,
                ]),
            ],
            */
        ];
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

        $inputType = $attributes[$name][self::INPUT];
        $allowInput = $attributes[$name][self::ALLOW_INPUT];
        $isMultiple = $attributes[$name][self::MULTIPLE];

        $inputManager = new AttributeInputManager();
        $input = $inputManager->getSymfonyInputType($inputType);
        $config = $this->getValueFieldConfig($name, $attributes);
        $config['attr']['style'] .= ' width: 250px';

        if (strtolower($input) === 'select2') {
            $input = new Select2ComboBoxType();
        }

        if ($isMultiple) {
            $input = new Select2ComboBoxType();

            $config['multiple'] = $isMultiple;
            $config['attr']['style'] .= ' width: 250px';
            $config['choices'] = [];
            $config['attr']['class'] .= ' ajax';

            if ($allowInput) {
                $config['attr']['tags'] = true;
            }
        }
        else {
            if ($allowInput) {
                $config['attr']['class'] .= ' attribute_autocomplete';
            } else {
                $config['disabled'] = true;
            }
        }

        $builder->add(self::FIELD_VALUE, $input, $config);

        return $this;
    }

    public function foo()
    {
        $builder->add(self::FIELD_KEY, new AutosuggestType(), [
            'label' => 'Attribute key',
            'url' => '/product-management/attributes/keys',
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'methods' => [
                        function ($key, ExecutionContextInterface $context) {
                            $keyCount = $this->productManagementQueryContainer
                                ->queryProductAttributeKey()
                                ->joinSpyProductManagementAttribute()
                                ->filterByKey($key)
                                ->count();

                            if ($keyCount > 0) {
                                $context->addViolation('Attribute key is already used');
                            }
                        },
                    ],
                    'groups' => [self::GROUP_UNIQUE_KEY]
                ]),
            ],
            'disabled' => $options[self::OPTION_IS_UPDATE],
        ]);
    }

}
