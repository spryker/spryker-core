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
    const OPTION_SUPER_ATTRIBUTES = ProductConcreteFormAdd::OPTION_SUPER_ATTRIBUTES;

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
            'dropdown',
            Select2ComboBoxType::class,
            [
                'choices' => array_flip($productmanagementAttributeTransfer->getValues()->getArrayCopy()),
                'choices_as_values' => true,
                'label' => $productmanagementAttributeTransfer->getKey(),
                'required' => false,
                'attr' => [
                    'class' => 'super-attribute-dropdown-input',
                ],
            ]
        );

        if ($productmanagementAttributeTransfer->getAllowInput()) {
            $formGoupBuilder->add(
                'checkbox',
                CheckboxType::class,
                [
                    'label' => 'Use custom value',
                    'attr' => [
                        'class' => 'super-attribute-checkbox-input',
                    ],
                ]
            );

            $formGoupBuilder->add(
                'input',
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
