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

class GeneralForm extends AbstractType
{
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_VALUES = 'productOptionValues';
    const FIELD_ID_PRODUCT_OPTION_GROUP = 'idProductOptionGroup';
    const FIELD_NAME = 'name';

    const OPTION_TAX_SETS = 'optionTaxSets';

    /**
     * @var \Spryker\Zed\ProductOption\Communication\Form\ProductOptionForm
     */
    protected $productOptionForm;

    /**
     * GeneralForm constructor.
     */
    public function __construct(ProductOptionForm $productOptionForm)
    {
        $this->productOptionForm = $productOptionForm;
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
            ->addTaxSetField($builder, $options)
            ->addIdProductOptionGroup($builder);
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
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_option_general';
    }
}
