<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class MultipleVouchersForm extends SingleVoucherForm
{

    const FIELD_NUMBER = 'number';

    /**
     * Prepares form
     *
     * @return VoucherForm
     */
    protected function buildFormFields()
    {
        $this
            ->addText(self::FIELD_NUMBER, [
                'label' => 'Number',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(1)
                ],
            ])
        ;

        parent::buildFormFields();
    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        return [
            self::FIELD_NUMBER => 2,
        ];
    }

}
