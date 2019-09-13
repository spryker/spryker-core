<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Item;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ItemFormType extends AbstractType
{
    public const OPTION_ORDER_ITEMS_CHOICES = 'items_choices';
    public const FIELD_IS_UPDATED = 'is_updated';
    public const FIELD_SHIPMENT_SELECTED_ITEMS = 'selected_items';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'order_item';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::FIELD_SHIPMENT_SELECTED_ITEMS)
            ->setDefault(static::OPTION_ORDER_ITEMS_CHOICES, []);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $selectedItems = $options[ShipmentGroupFormType::FIELD_SHIPMENT_SELECTED_ITEMS];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($selectedItems) {
            $item = $event->getData();
            $form = $event->getForm();

            $isSelected = in_array($item->getIdSalesOrderItem(), $selectedItems);

            $form->add(static::FIELD_IS_UPDATED, CheckboxType::class, [
                'label' => false,
                'required' => false,
                'data' => $isSelected,
                'attr' => [
                    'disabled' => $isSelected,
                ],
            ]);
        });

        return $this;
    }
}
