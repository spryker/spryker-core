<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductOptionGroupForm extends AbstractType
{
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_VALUES = 'productOptionValues';
    const FIELD_ID_PRODUCT_OPTION_GROUP = 'idProductOptionGroup';
    const FIELD_NAME = 'name';
    const FIELD_VALUE_TRANSLATIONS = 'productOptionValueTranslations';

    const OPTION_TAX_SETS = 'optionTaxSets';
    const PRODUCTS_TO_BE_ASSIGNED = 'products_to_be_assigned';
    const PRODUCTS_TO_BE_DEASSIGNED = 'products_to_be_deassigned';

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm
     */
    protected $productOptionForm;

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueTranslationForm
     */
    protected $productOptionValueTranslationForm;

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm $productOptionForm
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueTranslationForm $productOptionValueTranslationForm
     */
    public function __construct(
        ProductOptionValueForm $productOptionForm,
        ProductOptionValueTranslationForm $productOptionValueTranslationForm
    ) {
        $this->productOptionForm = $productOptionForm;
        $this->productOptionValueTranslationForm = $productOptionValueTranslationForm;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addValuesFields($builder)
            ->addValueTranslationFields($builder)
            ->addTaxSetField($builder, $options)
            ->addIdProductOptionGroup($builder)
            ->addProductsToBeAssignedField($builder)
            ->addProductsToBeDeassignedField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(self::OPTION_TAX_SETS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, 'text', [
            'label' => 'Group name',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValuesFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUES, 'collection', array(
            'type' => $this->productOptionForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ));

        $builder->get(self::FIELD_VALUES)
            ->addModelTransformer(new CallbackTransformer(
                function ($productOptionValues) {
                    if ($productOptionValues) {
                        return (array)$productOptionValues;
                    }
                },
                function ($productOptionValues) {
                    return new \ArrayObject($productOptionValues);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValueTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUE_TRANSLATIONS, 'collection', array(
            'type' => $this->productOptionValueTranslationForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ));

        $builder->get(self::FIELD_VALUE_TRANSLATIONS)
            ->addModelTransformer(new CallbackTransformer(
                function ($productOptionValueTranslations) {
                    if ($productOptionValueTranslations) {
                        return (array)$productOptionValueTranslations;
                    }
                },
                function ($productOptionValueTranslations) {
                    return new \ArrayObject($productOptionValueTranslations);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTaxSetField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(
            self::FIELD_TAX_SET_FIELD,
            'choice',
            [
                'label' => 'Tax set',
                'choices' => $options[self::OPTION_TAX_SETS],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductOptionGroup(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_OPTION_GROUP, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCTS_TO_BE_ASSIGNED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_ASSIGNED)
            ->addModelTransformer(new CallbackTransformer(
                function ($productsToBeAssigned) {
                    if ($productsToBeAssigned) {
                        return implode(',', $productsToBeAssigned);
                    }
                },
                function ($productsToBeAssigned) {
                    return explode(',', $productsToBeAssigned);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeDeassignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCTS_TO_BE_DEASSIGNED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_DEASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_DEASSIGNED)
            ->addModelTransformer(new CallbackTransformer(
                function ($productsToBeDeassigned) {
                    if ($productsToBeDeassigned) {
                        return implode(',', $productsToBeDeassigned);
                    }
                },
                function ($productsToBeDeassigned) {
                    return explode(',', $productsToBeDeassigned);
                }
            ));

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_option_general';
    }
}
