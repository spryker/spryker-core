<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ShipmentFormEdit extends ShipmentFormCreate
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOrderItemsForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FORM_SALES_ORDER_ITEMS, CollectionType::class, [
            'entry_type' => ItemForm::class,
            'entry_options' => [
                'label' => false,
                static::FIELD_SHIPMENT_SELECTED_ITEMS => $builder->getOption(static::FIELD_SHIPMENT_SELECTED_ITEMS),
            ],
        ]);
        return $this;
    }
}
