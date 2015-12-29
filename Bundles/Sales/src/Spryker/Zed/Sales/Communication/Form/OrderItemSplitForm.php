<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class OrderItemSplitForm extends AbstractForm
{

    const QUANTITY = 'quantity';
    const ID_ORDER_ITEM = 'id_order_item';
    const ID_ORDER = 'id_order';
    const VALIDATE_MESSAGE_NUMERIC = 'Please provide numeric value.';
    const VALIDATION_MESSAGE_QUANTITY = 'Please provide quantity.';

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'order_item_split';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::QUANTITY, 'text', [
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
            ])
            ->add(self::ID_ORDER_ITEM, 'hidden')
            ->add(self::ID_ORDER, 'hidden')
            ->add('Split', 'submit');
    }

    /**
     * Set the values for fields
     *
     * @return self
     */
    public function populateFormFields()
    {
        return [];
    }

}
