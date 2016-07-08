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

    const GENERAL = 'general';
    const ATTRIBUTE_METADATA = 'attribute_metadata';
    const ATTRIBUTE_VALUES = 'attribute_values';
    const TAX_SET = 'tax_set';
    const PRICE_AND_STOCK = 'price_and_stock';
    const VARIANT_METADATA = 'variant_metadata';
    const VARIANT_VALUES = 'variant_values';
    const SEO = 'seo';
    const ID_LOCALE = 'id_locale';

    const VALIDATION_GROUP_ATTRIBUTE_METADATA = 'validation_group_attribute_metadata';
    const VALIDATION_GROUP_ATTRIBUTE_VALUES = 'validation_group_attribute_values';
    const VALIDATION_GROUP_PRICE_AND_STOCK = 'validation_group_price_and_stock';
    const VALIDATION_GROUP_SEO = 'validation_group_seo';
    const VALIDATION_GROUP_GENERAL = 'validation_group_general';
    const VALIDATION_GROUP_VARIANT_METADATA = 'validation_group_variant_metadata';


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

        $resolver->setRequired(self::ATTRIBUTE_METADATA);
        $resolver->setRequired(self::ATTRIBUTE_VALUES);
        $resolver->setRequired(self::TAX_SET);
        $resolver->setRequired(self::ID_LOCALE);

        $resolver->setDefaults([
            'cascade_validation' => true,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                return [
                    Constraint::DEFAULT_GROUP,
                    self::VALIDATION_GROUP_GENERAL,
                    self::VALIDATION_GROUP_ATTRIBUTE_METADATA,
                    self::VALIDATION_GROUP_ATTRIBUTE_VALUES,
                    self::VALIDATION_GROUP_PRICE_AND_STOCK,
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
            ->addAttributeMetadataForm($builder, $options[self::ATTRIBUTE_METADATA])
            ->addAttributeValuesForm($builder, $options[self::ATTRIBUTE_VALUES])
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
    protected function addAttributeMetadataForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_METADATA, 'collection', [
                'type' => new ProductFormAttributeMetadata(
                    $options,
                    self::VALIDATION_GROUP_ATTRIBUTE_METADATA
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
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_METADATA]
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
    protected function addAttributeValuesForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_VALUES, 'collection', [
                'type' => new ProductFormAttributeValues(
                    $options,
                    self::VALIDATION_GROUP_ATTRIBUTE_VALUES
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
                                $context->addViolation('Please select at least one attribute value');
                            }
                        },
                    ],
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_VALUES]
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
            ->add(self::PRICE_AND_STOCK, new ProductFormPrice($options, self::VALIDATION_GROUP_PRICE_AND_STOCK), [
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
                    'groups' => [self::VALIDATION_GROUP_PRICE_AND_STOCK]
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

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addVariantMetadataForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(self::ATTRIBUTE_METADATA, 'collection', [
                'label' => false,
                'type' => new ProductFormVariantMetadata(
                    $options,
                    self::VALIDATION_GROUP_VARIANT_METADATA
                ),
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
                    'groups' => [self::VALIDATION_GROUP_ATTRIBUTE_METADATA]
                ])]
            ]);

        return $this;
    }

}
