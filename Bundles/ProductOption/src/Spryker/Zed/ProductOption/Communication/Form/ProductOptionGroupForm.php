<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use Spryker\Zed\ProductOption\Communication\Form\Transformer\ArrayToArrayObjectTransformer;
use Spryker\Zed\ProductOption\Communication\Form\Transformer\StringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductOptionGroupForm extends AbstractType
{
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_VALUES = 'productOptionValues';
    const FIELD_ID_PRODUCT_OPTION_GROUP = 'idProductOptionGroup';
    const FIELD_NAME = 'name';
    const FIELD_VALUE_TRANSLATIONS = 'productOptionValueTranslations';
    const FIELD_GROUP_NAME_TRANSLATIONS = 'groupNameTranslations';

    const OPTION_TAX_SETS = 'optionTaxSets';

    const PRODUCTS_TO_BE_ASSIGNED = 'products_to_be_assigned';
    const PRODUCTS_TO_BE_DE_ASSIGNED = 'products_to_be_de_assigned';
    const PRODUCT_OPTION_VALUES_TO_BE_REMOVED = 'product_option_values_to_be_removed';


    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm
     */
    protected $productOptionForm;

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm
     */
    protected $productOptionTranslationForm;

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\Transformer\ArrayToArrayObjectTransformer
     */
    protected $arrayToArrayObjectTransformer;

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\Transformer\StringToArrayTransformer
     */
    protected $stringToArrayTransformer;

    /**
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionValueForm $productOptionForm
     * @param \Spryker\Zed\ProductOption\Communication\Form\ProductOptionTranslationForm $productOptionTranslationForm
     * @param \Spryker\Zed\ProductOption\Communication\Form\Transformer\ArrayToArrayObjectTransformer $arrayToArrayObjectTransformer
     * @param \Spryker\Zed\ProductOption\Communication\Form\Transformer\StringToArrayTransformer $stringToArrayTransformer
     */
    public function __construct(
        ProductOptionValueForm $productOptionForm,
        ProductOptionTranslationForm $productOptionTranslationForm,
        ArrayToArrayObjectTransformer $arrayToArrayObjectTransformer,
        StringToArrayTransformer $stringToArrayTransformer
    ) {
        $this->productOptionForm = $productOptionForm;
        $this->productOptionTranslationForm = $productOptionTranslationForm;
        $this->arrayToArrayObjectTransformer = $arrayToArrayObjectTransformer;
        $this->stringToArrayTransformer = $stringToArrayTransformer;
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
            ->addGroupNameTranslationFields($builder)
            ->addTaxSetField($builder, $options)
            ->addIdProductOptionGroup($builder)
            ->addProductsToBeAssignedField($builder)
            ->addProductsToBeDeAssignedField($builder)
            ->addProductOptionValuesToBeRemoved($builder);
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
            'label' => 'Group name translation key',
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
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

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
            'type' => $this->productOptionTranslationForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ));

        $builder->get(self::FIELD_VALUE_TRANSLATIONS)
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGroupNameTranslationFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_GROUP_NAME_TRANSLATIONS, 'collection', array(
            'type' => $this->productOptionTranslationForm,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
        ));

        $builder->get(self::FIELD_GROUP_NAME_TRANSLATIONS)
            ->addModelTransformer($this->arrayToArrayObjectTransformer);

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
            ->addModelTransformer($this->stringToArrayTransformer);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsToBeDeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCTS_TO_BE_DE_ASSIGNED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCTS_TO_BE_DE_ASSIGNED,
                ],
            ]);

        $builder->get(self::PRODUCTS_TO_BE_DE_ASSIGNED)
            ->addModelTransformer($this->stringToArrayTransformer);

        return $this;
    }

    /***
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOptionValuesToBeRemoved(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED, 'hidden', [
                'attr' => [
                    'id' => self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED,
                ],
            ]);

        $builder->get(self::PRODUCT_OPTION_VALUES_TO_BE_REMOVED)
            ->addModelTransformer($this->stringToArrayTransformer);

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
