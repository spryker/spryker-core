<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;

class StockSubForm extends AbstractType
{
    const FIELD_QUANTITY = 'quantity';
    const FIELD_STOCK_TYPE = 'stockType';
    const FIELD_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';
    const FIELD_FK_STORE = 'fkStore';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addQuantityField($builder)
            ->addStockTypeField($builder)
            ->addFkStoreField($builder)
            ->addIsNeverOutOfStockCheckbox($builder);
    }

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return 'stock_form';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_QUANTITY, 'text', [
            'label' => 'Quantity',
            'constraints' => [
                new Required(),
                new Regex(['pattern' => '/[\d]+/']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStockTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_STOCK_TYPE, 'text', [
            'label' => 'Stock Type',
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsNeverOutOfStockCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_NEVER_OUT_OF_STOCK, 'checkbox', [
            'label' => 'Never out of stock',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkStoreField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_STORE, 'hidden', [
            'label' => 'Store',
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['store'] = $form->getData()->getStore();
    }
}
