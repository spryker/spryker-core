<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductConcreteSuperAttributeForm extends AbstractType
{
    public const FIELD_DROPDOWN = 'dropdown';
    public const FIELD_INPUT = 'input';
    public const FIELD_CHECKBOX = 'checkbox';
    public const OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER = 'OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER);
    }

    /**
     * @retun void
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDropdownField($builder, $options)->addCustomValueFields($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDropdownField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_DROPDOWN,
            Select2ComboBoxType::class,
            [
                'choices' => $this->extractAttributeValuesFromTransfer($options[static::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER]),
                'choice_label' => function ($choiceValue) {
                    return $choiceValue;
                },
                'label' => false,
                'attr' => [
                    'class' => 'super-attribute-dropdown-input',
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCustomValueFields(FormBuilderInterface $builder, array $options)
    {
        $productManagementAttributeTransfer = $options[static::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER];

        if (!$productManagementAttributeTransfer->getAllowInput()) {
            return $this;
        }

        $builder->add(
            static::FIELD_CHECKBOX,
            CheckboxType::class,
            [
                'label' => 'Use custom value',
                'attr' => [
                    'class' => 'super-attribute-checkbox-input',
                ],
            ]
        );

        $builder->add(
            static::FIELD_INPUT,
            TextType::class,
            [
                'label' => false,
                'attr' => [
                    'class' => 'hidden super-attribute-text-input',
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return array
     */
    protected function extractAttributeValuesFromTransfer(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        return array_map(function (ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer) {
            return $productManagementAttributeValueTransfer->getValue();
        }, $productManagementAttributeTransfer->getValues()->getArrayCopy());
    }
}
