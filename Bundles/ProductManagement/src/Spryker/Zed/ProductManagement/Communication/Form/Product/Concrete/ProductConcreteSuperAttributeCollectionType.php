<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributesNotBlank;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeUniqueCombination;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class ProductConcreteSuperAttributeCollectionType extends AbstractType
{
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'form_product_concrete_super_attributes';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL = 'Super attributes';
    public const OPTION_SUPER_ATTRIBUTES = 'option_super_attributes';
    public const OPTION_ID_PRODUCT_ABSTRACT = 'option_id_product_abstract';
    public const CONTAINER_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'container_product_concrete_super_attributes';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL_CLASS = 'super_attributes_label';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addAssignProductAbstractIdsField($builder, $options)
            ->addAssignProductConcreteOptionsSuperAttributes($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAssignProductAbstractIdsField(FormBuilderInterface $builder, $options)
    {
        $builder->add(
            static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES,
            FormType::class,
            [
                'compound' => true,
                'label' => static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL,
                'label_attr' => ['class' => static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL_CLASS],
                'constraints' => $this->prepareProductConcreteSuperAttributeFormConstraints($builder, $options),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return array
     */
    protected function prepareProductConcreteSuperAttributeFormConstraints(FormBuilderInterface $builder, array $options): array
    {
        if (!$builder->getOption(static::OPTION_SUPER_ATTRIBUTES)) {
            return [];
        }

        return [
            new ProductAttributesNotBlank(),
            new ProductAttributeUniqueCombination(
                $this->getFactory()->getProductFacade(),
                (int)$options[static::OPTION_ID_PRODUCT_ABSTRACT],
                $this->getFactory()->createProductConcreteSuperAttributeFilterHelper()
            ),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAssignProductConcreteOptionsSuperAttributes(FormBuilderInterface $builder, array $options)
    {
        foreach ($options[static::OPTION_SUPER_ATTRIBUTES] as $productManagementAttributeTransfer) {
            $builder
                ->get(static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES)
                ->add(
                    $productManagementAttributeTransfer->getKey(),
                    ProductConcreteSuperAttributeForm::class,
                    [
                        ProductConcreteSuperAttributeForm::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER => $productManagementAttributeTransfer,
                        'label' => $productManagementAttributeTransfer->getKey(),
                        'error_bubbling' => false,
                        'attr' => [
                            'class' => 'super-attribute-inputs-group',
                        ],
                        'constraints' => [
                            new ProductAttributeType(
                                $productManagementAttributeTransfer,
                                [
                                    'checkbox' => ProductConcreteSuperAttributeForm::FIELD_CHECKBOX,
                                    'input' => ProductConcreteSuperAttributeForm::FIELD_INPUT,
                                    'type' => ProductAttributeType::TYPE_NUMBER,
                                ]
                            ),
                        ],
                    ]
                );
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_SUPER_ATTRIBUTES => [],
            static::OPTION_ID_PRODUCT_ABSTRACT => 0,
        ]);
    }
}
