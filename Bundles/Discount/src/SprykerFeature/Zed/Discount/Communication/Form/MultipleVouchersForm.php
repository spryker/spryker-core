<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class MultipleVouchersForm extends SingleVoucherForm
{

    const FIELD_NUMBER = 'number';
    const MINIMUM_VOUCHERS_TO_GENERATE = 2;

    /**
     * Prepares form
     *
     * @return VoucherForm
     */
    protected function buildFormFields()
    {
        $this
            ->addText(static::FIELD_NUMBER, [
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
            static::FIELD_NUMBER => static::MINIMUM_VOUCHERS_TO_GENERATE,
        ];
    }

}
