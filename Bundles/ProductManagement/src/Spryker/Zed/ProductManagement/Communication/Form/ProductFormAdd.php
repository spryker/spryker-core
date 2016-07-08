<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormAdd extends AbstractType
{

    const FIELD_DESCRIPTION = 'description';
    const FIELD_NAME = 'name';
    const FIELD_SKU = 'sku';

    const ATTRIBUTE_ABSTRACT = 'attribute_abstract';
    const ATTRIBUTE_VARIANT = 'attribute_variant';
    const GENERAL = 'general';
    const ID_LOCALE = 'id_locale';
    const PRICE_AND_STOCK = 'price_and_stock';
    const TAX_SET = 'tax_set';
    const SEO = 'seo';

    const VALIDATION_GROUP_ATTRIBUTE_ABSTRACT = 'validation_group_attribute_abstract';
    const VALIDATION_GROUP_ATTRIBUTE_VARIANT = 'validation_group_attribute_variant';
    const VALIDATION_GROUP_GENERAL = 'validation_group_general';
    const VALIDATION_GROUP_PRICE_AND_TAX = 'validation_group_price_and_tax';
    const VALIDATION_GROUP_SEO = 'validation_group_seo';


    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormAdd';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::ATTRIBUTE_ABSTRACT);
        $resolver->setRequired(self::ATTRIBUTE_VARIANT);
        $resolver->setRequired(self::TAX_SET);
        $resolver->setRequired(self::ID_LOCALE);

        $resolver->setDefaults([
            'cascade_validation' => true,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                return [
                    Constraint::DEFAULT_GROUP,
                    self::VALIDATION_GROUP_GENERAL,
                    self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT,
                    self::VALIDATION_GROUP_PRICE_AND_TAX,
                    self::VALIDATION_GROUP_ATTRIBUTE_VARIANT,
                    self::VALIDATION_GROUP_SEO,
                ];
            }
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
            ->addSkuField($builder)
            ->addGeneralForm($builder)
            ->addAttributeAbstractForm($builder, $options[self::ATTRIBUTE_ABSTRACT])
            ->addAttributeVariantForm($builder, $options[self::ATTRIBUTE_VARIANT])
            ->addPriceForm($builder, $options[self::TAX_SET])
            ->addSeoForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, 'text', [
                'label' => 'SKU',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::GENERAL, 'collection', [
                'type' => new ProductFormGeneral(),
                'constraints' => [new Callback([
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            $selectedAttributes = [];
                            foreach ($dataToValidate as $locale => $localizedData) {
                                foreach ($localizedData as $key => $value) {
                                    if (!empty($value)) {
                                        $selectedAttributes[] = $value;
                                        break;
                                        break 2;
                                    }
                                }
                            }

                            if (empty($selectedAttributes)) {
                                $context->addViolation('Please enter at least Sku and Name of the product');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_GENERAL]
                ])]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeAbstractForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_ABSTRACT, 'collection', [
                'type' => new ProductFormAttributeAbstract(
                    $options,
                    self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT
                ),
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (empty($selectedAttributes)) {
                                $context->addViolation('Please select at least one attribute group');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_ABSTRACT]
                ])]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeVariantForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_VARIANT, 'collection', [
                'type' => new ProductFormAttributeVariant(
                    $options,
                    self::VALIDATION_GROUP_ATTRIBUTE_VARIANT
                ),
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (empty($selectedAttributes)) {
                                $context->addViolation('Please select at least one variant attribute value');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_VARIANT]
                ])]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::PRICE_AND_STOCK, new ProductFormPrice($options, self::VALIDATION_GROUP_PRICE_AND_TAX), [
                'label' => false,
                'constraints' => [new Callback([
                    'methods' => [
                        function ($dataToValidate, ExecutionContextInterface $context) {
                            if ((int)$dataToValidate[ProductFormPrice::FIELD_PRICE] <= 0) {
                                $context->addViolation('Please Price information under Price & Taxes');
                            }

                            if ((int)$dataToValidate[ProductFormPrice::FIELD_TAX_RATE] <= 0) {
                                $context->addViolation('Please Tax information under Price & Taxes');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_PRICE_AND_TAX]
                ])]
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSeoForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::SEO, 'collection', [
                'label' => false,
                'type' => new ProductFormSeo(self::VALIDATION_GROUP_SEO),
                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                }
                            }

                            if (empty($selectedAttributes)) {
                                //$context->addViolation('Please enter meta information');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_SEO]
                ])]
            ]);

        return $this;
    }

}
