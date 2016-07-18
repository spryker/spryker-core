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
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormAttributeVariant extends ProductFormAttributeAbstract
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options = [])
    {
        $attributes = $options[ProductFormAttributeAbstract::OPTION_ATTRIBUTE];

        $name = $builder->getName();
        $isDisabled = $attributes[$name][self::VALUE_DISABLED];
        $input = $attributes[$name][self::INPUT];

        $builder->add(self::FIELD_VALUE, $input, [
            'disabled' => $isDisabled,
            'label' => false,
            'attr' => [
                'style' => 'width: 250px !important',
                'class' => 'attribute_metadata_value',
                'product_specific' => $attributes[$name][self::PRODUCT_SPECIFIC]
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
/*            'constraints' => [
                new AttributeFieldNotBlank([
                    'attributeFieldValue' => self::FIELD_VALUE,
                    'attributeCheckboxFieldName' => self::FIELD_NAME,
                ]),
            ],*/
        ]);

        return $this;
    }

}
