<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class StockForm extends AbstractType
{
    const FIELD_HIDDEN_STOCK_PRODUCT_ID = 'id_stock_product';
    const FIELD_HIDDEN_FK_STOCK = 'fk_stock';

    const FIELD_TYPE = 'type';
    const FIELD_QUANTITY = 'quantity';
    const FIELD_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @return string
     */
    public function getName()
    {
        return 'StockConcreteForm';
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
            ->addStockIdHiddenField($builder, $options)
            ->addProductStockIdHiddenField($builder, $options)
            ->addTypeField($builder, $options)
            ->addQuantityField($builder, $options)
            ->addIsNeverOutOfStockCheckbox($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStockIdHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_HIDDEN_FK_STOCK, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductStockIdHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_HIDDEN_STOCK_PRODUCT_ID, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_TYPE, 'text', [
            'label' => 'Type',
            'required' => true,
            'read_only' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_QUANTITY, 'text', [
            'label' => 'Quantity',
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
    protected function addIsNeverOutOfStockCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(StockForm::FIELD_IS_NEVER_OUT_OF_STOCK, 'checkbox', [
            'label' => 'Never out of stock',
        ]);

        return $this;
    }
}
