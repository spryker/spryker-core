<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class OrderItemSplitForm extends AbstractForm
{

    const QUANTITY = 'quantity';
    const ID_ORDER_ITEM = 'id_order_item';
    const ID_ORDER = 'id_order';
    const VALIDATE_MESSAGE_NUMERIC = 'Please provide numeric value.';
    const VALIDATION_MESSAGE_QUANTITY = 'Please provide quantity.';

    /**
     * Prepares form
     *
     * @return $this
     */
    protected function buildFormFields()
    {
        return $this->addText(
            self::QUANTITY,
            [
                'label' => 'Quantity',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank([
                        'message' => self::VALIDATION_MESSAGE_QUANTITY,
                    ]),
                    $this->getConstraints()->createConstraintRegex([
                        'pattern' => '/^\d+$/',
                        'message' => self::VALIDATE_MESSAGE_NUMERIC,
                    ]),
                ],
            ]
        )
            ->addHidden(self::ID_ORDER_ITEM)
            ->addHidden(self::ID_ORDER)
            ->addSubmit('Split');
    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        return [];
    }

}
