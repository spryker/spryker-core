<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class GeneralForm extends AbstractType
{
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_VALUES = 'values';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addValuesFields($builder)
            ->addTaxSetField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValuesFields(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALUES, 'collection', array(
            'type' => new ProductOptionForm(),
            'allow_add' => true,
            'prototype' => true,
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
                'choices' => [],
            ]
        );

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
