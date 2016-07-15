<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Communication\Form\Constraints\AttributeFieldNotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    /**
     * @var array
     */
    protected $attributeValues;


    /**
     * @param string $name
     * @param array $attributeValues
     */
    public function __construct($name, array $attributeValues)
    {
        parent::__construct($name);
        $this->attributeValues = $attributeValues;
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
        $name = $builder->getName();
        $label = $name;
        $isDisabled = true;

        if (isset($this->attributeValues[$name])) {
            $label = $this->attributeValues[$name][self::LABEL];
            $isDisabled = $this->attributeValues[$name][self::NAME_DISABLED];
        }

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $label,
                'disabled' => $isDisabled,
                'attr' => [
                    'class' => 'attribute_metadata_checkbox',
                    'product_specific' => $this->attributeValues[$name][self::PRODUCT_SPECIFIC]
                ],
            ]);

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
        $isDisabled = $this->attributeValues[$name][self::VALUE_DISABLED];
        $input = $this->attributeValues[$name][self::INPUT];

        $builder->add(self::FIELD_VALUE, $input, [
            'disabled' => $isDisabled,
            'label' => false,
            'attr' => [
                'style' => 'width: 250px !important',
                'class' => 'attribute_metadata_value',
                'product_specific' => $this->attributeValues[$name][self::PRODUCT_SPECIFIC]
            ],
            'constraints' => [
                new AttributeFieldNotBlank([
                    'attributeFieldValue' => self::FIELD_VALUE,
                    'attributeCheckboxFieldName' => self::FIELD_NAME,
                ]),
            ],
        ]);

        return $this;
    }

}
