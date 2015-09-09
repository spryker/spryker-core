<?php

namespace SprykerFeature\Zed\Refund\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Refund\Persistence\Propel\Base\SpyRefundQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;

class RefundForm extends AbstractForm
{

    const FIELD_QUANTITY = 'quantity';

    protected $refundQuery;

    /**
     * @param SpyRefundQuery $refundQuery
     */
    public function __construct()
    {
        //$this->refundQuery = $refundQuery;
    }

    /**
     * Prepares form
     *
     * @return RefundForm
     */
    protected function buildFormFields()
    {
        $this
            ->addText(static::FIELD_QUANTITY, [
                'label' => 'Number',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;


    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        return [
        ];
    }

}
