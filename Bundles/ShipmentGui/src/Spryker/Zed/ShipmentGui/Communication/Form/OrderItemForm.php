<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentGui\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ShipmentGui\SalesConfig getConfig()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesRepositoryInterface getRepository()
 */
class OrderItemForm extends AbstractType
{
    public const FIELD_ORDER_ITEM_ID = 'field_order_item_id';

    public const FIELD_ORDER_ITEM_NAME = 'field_order_item_name';

    public const FIELD_ORDER_ITEM_SKU = 'field_order_item_sku';

    public const FIELD_ORDER_ITEM_ASSIGNED = 'field_order_item_assigned';

    public const FIELD_ORDER_ITEM_QUANTITY = 'field_order_item_quantity';




    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'orderItemForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {

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
            ->addOrderItemId($builder, $options)
            ->addOrderItemName($builder, $options)
            ->addOrderItemSku($builder, $options)
            ->addOrderItemCheckbox($builder, $options)
            ->addQuantityField($builder, $options)
        ;
    }

    protected function addOrderItemId(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ORDER_ITEM_ID,
            HiddenType::class, [
        ]);

        return $this;
    }

    protected function addOrderItemSku(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ORDER_ITEM_SKU,
            HiddenType::class, [
                'data' => '000002'
        ]);

        return $this;
    }

    protected function addOrderItemName(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ORDER_ITEM_NAME,
            HiddenType::class,
            []
        );

        return $this;
    }

    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ORDER_ITEM_QUANTITY,
            ChoiceType::class, [
            'choices' => [
                1  => 'x1',
                2 => 'x2',
            ],
        ]);

        return $this;
    }

    protected function addOrderItemCheckbox(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ORDER_ITEM_ASSIGNED,
            CheckboxType::class, [
            'required' => false,
            ]
        );

        return $this;
    }
}
