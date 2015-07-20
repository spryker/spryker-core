<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;

class OrderItemSplitForm extends AbstractForm
{
    /**
     * Prepares form
     *
     * @return $this
     */
    protected function buildFormFields()
    {
        return $this->addText(
            'quantity',
            [
                'label' => 'Quantity',
                'constraints' => [
                    new Constraints\NotBlank([
                        'message' => 'Please provide quantity'
                    ]),
                    new Constraints\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Please provide numeric value'
                    ])
                ],
                'attr' => [
                    'size' => 10
                ]
            ]
        )
            ->addHidden('id_order_item')
            ->addHidden('id_order')
            ->addSubmit();
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
