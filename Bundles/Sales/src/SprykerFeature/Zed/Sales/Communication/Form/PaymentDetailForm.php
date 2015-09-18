<?php

namespace SprykerFeature\Zed\Sales\Communication\Form;

use Symfony\Component\Validator\Constraints\NotBlank;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneDetailQuery;

class PaymentDetailForm extends AbstractForm
{

    const BIC = 'bic';
    const IBAN = 'iban';

    const SUBMIT = 'submit';

    /**
     * @var SpyPaymentPayoneDetailQuery
     */
    private $paymentDetailsQuery;

    /**
     * @param SpyPaymentPayoneDetailQuery $paymentDetailsQuery
     */
    public function __construct(SpyPaymentPayoneDetailQuery $paymentDetailsQuery)
    {
        $this->paymentDetailsQuery = $paymentDetailsQuery;
    }

    /**
     * @return PaymentDetailForm
     */
    protected function buildFormFields()
    {
        return $this
            ->addText(self::IBAN, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addText(self::BIC, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $paymentDetails = $this->paymentDetailsQuery->findOne();

        return [
            self::IBAN => $paymentDetails->getIban(),
            self::BIC => $paymentDetails->getBic(),
        ];
    }

}
