<?php

namespace SprykerFeature\Zed\Refund\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Refund\Persistence\Propel\Base\SpyRefundQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Required;

class RefundForm extends AbstractForm
{

    const FIELD_COMMENT = 'comment';

    const FIELD_AMMOUNT = 'amount';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return RefundForm
     */
    protected function buildFormFields()
    {
        $this
            ->addNumber(self::FIELD_AMMOUNT, [
                'label' => 'Refund Amount',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addTextarea(static::FIELD_COMMENT, [
                'label' => 'Comment',
                'attr' => [
                    'rows' => 7,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;


    }

    /**
     * @return RefundForm
     */
    protected function populateFormFields()
    {
        return [];
    }

}
