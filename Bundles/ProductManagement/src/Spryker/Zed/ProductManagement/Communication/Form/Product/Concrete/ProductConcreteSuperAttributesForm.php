<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormAdd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductConcreteSuperAttributesForm extends AbstractType
{
    public const OPTION_SUPER_ATTRIBUTES = ProductConcreteFormAdd::OPTION_SUPER_ATTRIBUTES;
    public const FIELD_DROPDOWN = 'dropdown';
    public const FIELD_INPUT = 'input';
    public const FIELD_CHECKBOX = 'checkbox';

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
        /**
         * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productmanagementAttributeTransfer
         */
        foreach ($options[static::OPTION_SUPER_ATTRIBUTES] as $productmanagementAttributeTransfer) {
            $this->addInputGroup($builder, $productmanagementAttributeTransfer);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SUPER_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productmanagementAttributeTransfer
     *
     * @return void
     */
    protected function addInputGroup(FormBuilderInterface $builder, ProductManagementAttributeTransfer $productmanagementAttributeTransfer)
    {
        $formGoupBuilder = $builder->create(
            $productmanagementAttributeTransfer->getKey(),
            FormType::class,
            [
                'inherit_data' => true,
                'label' => false,
                'attr' => [
                    'class' => 'super-attribute-inputs-group',
                ],
            ]
        );

        $formGoupBuilder->add(
            static::FIELD_DROPDOWN,
            Select2ComboBoxType::class,
            [
                'choices' => $productmanagementAttributeTransfer->getValues()->getArrayCopy(),
                'choice_label' => function ($choiceValue) {
                    return $choiceValue;
                },
                'label' => $productmanagementAttributeTransfer->getKey(),
                'required' => false,
                'attr' => [
                    'class' => 'super-attribute-dropdown-input',
                ],
            ]
        );

        if ($productmanagementAttributeTransfer->getAllowInput()) {
            $formGoupBuilder->add(
                static::FIELD_CHECKBOX,
                CheckboxType::class,
                [
                    'label' => 'Use custom value',
                    'attr' => [
                        'class' => 'super-attribute-checkbox-input',
                    ],
                ]
            );

            $formGoupBuilder->add(
                static::FIELD_INPUT,
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'hidden super-attribute-text-input',
                    ],
                ]
            );
        }

        $builder->add($formGoupBuilder);
    }
}
