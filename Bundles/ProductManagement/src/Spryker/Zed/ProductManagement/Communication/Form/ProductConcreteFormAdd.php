<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributeForm;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributesNotBlank;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeType;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeUniqueCombination;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 */
class ProductConcreteFormAdd extends ProductConcreteFormEdit
{
    use ProductConcreteSuperAttributeFormTrait;

    public const FIELD_SKU_AUTOGENERATE_CHECKBOX = 'sku_autogenerate_checkbox';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES = 'form_product_concrete_super_attributes';
    public const FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES_LABEL = 'Super attributes';
    public const OPTION_SUPER_ATTRIBUTES = 'option_super_attributes';
    public const OPTION_ID_PRODUCT_ABSTRACT = 'option_id_product_abstract';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addSkuField($builder, $options)
            ->addSkuAutogenerateCheckboxField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addProductAbstractIdHiddenField($builder)
            ->addProductConcreteIdHiddenField($builder)
            ->addGeneralLocalizedForms($builder)
            ->addPriceForm($builder, $options)
            ->addStockForm($builder, $options)
            ->addImageLocalizedForms($builder)
            ->addAssignBundledProductForm($builder, $options)
            ->addBundledProductsToBeRemoved($builder)
            ->addProductConcreteSuperAttributeForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_ID_PRODUCT_ABSTRACT);
        $resolver->setRequired(static::OPTION_SUPER_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder, array $options = [])
    {
        $formData = $builder->getData();

        $builder->add(static::FIELD_SKU, TextType::class, [
            'label' => 'SKU',
            'constraints' => [
                new NotBlank(),
                new SkuRegex(),
                new SkuUnique($this->getFactory()->getProductFacade()),
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $formData = $event->getData();

            if (!empty($formData[static::FIELD_SKU_AUTOGENERATE_CHECKBOX])) {
                $formData[static::FIELD_SKU] = $this->getGeneratedSku($formData, $options);
                $event->setData($formData);
            }
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuAutogenerateCheckboxField(FormBuilderInterface $builder)
    {
        if (!empty($builder->getOption(static::OPTION_SUPER_ATTRIBUTES))) {
            $builder->add(static::FIELD_SKU_AUTOGENERATE_CHECKBOX, CheckboxType::class, [
                'label' => 'Autogenerate SKU',
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductConcreteSuperAttributeForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = empty($builder->getOption(static::OPTION_SUPER_ATTRIBUTES)) ?
            [] :
            [
                new ProductAttributesNotBlank(),
                new ProductAttributeUniqueCombination(
                    $this->getFactory()->getProductFacade(),
                    (int)$options[static::OPTION_ID_PRODUCT_ABSTRACT]
                ),

            ];

        $builder->add(
            static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES,
            FormType::class,
            [
                'compound' => true,
                'label' => 'Super attributes',
                'constraints' => $constraints,
            ]
        );

        foreach ($options[static::OPTION_SUPER_ATTRIBUTES] as $productmanagementAttributeTransfer) {
            $builder->get(static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES)
                ->add(
                    $productmanagementAttributeTransfer->getKey(),
                    ProductConcreteSuperAttributeForm::class,
                    [
                        ProductConcreteSuperAttributeForm::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_TRANSFER => $productmanagementAttributeTransfer,
                        'label' => $productmanagementAttributeTransfer->getKey(),
                        'error_bubbling' => false,
                        'attr' => [
                            'class' => 'super-attribute-inputs-group',
                        ],
                        'constraints' => [
                            new ProductAttributeType($productmanagementAttributeTransfer),
                        ],
                    ]
                );
        }

        return $this;
    }

    /**
     * @param array $formData
     * @param array $options
     *
     * @return string|null
     */
    protected function getGeneratedSku(array $formData, array $options)
    {
        $idProductAbstract = $options[static::OPTION_ID_PRODUCT_ABSTRACT] ?? null;

        if ($idProductAbstract === null) {
            return null;
        }

        $productAbstractTransfer = $this->getFactory()->getProductFacade()->findProductAbstractById($idProductAbstract);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setAttributes(
            $this->getNonEmptyTransformedSubmittedSuperAttributes($formData[static::FORM_PRODUCT_CONCRETE_SUPER_ATTRIBUTES])
        );

        return $this->getFactory()->getProductFacade()->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);
    }

    /**
     * @param array $submittedAttributes
     *
     * @return array
     */
    protected function getNonEmptyTransformedSubmittedSuperAttributes(array $submittedAttributes)
    {
        return array_filter(
            $this->getTransformedSubmittedSuperAttributes($submittedAttributes),
            function ($submittedAttribute) {
                return $submittedAttribute !== null && $submittedAttribute !== '';
            }
        );
    }
}
