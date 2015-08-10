<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class CreditCardPseudoContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $pseudocardpan;

    /**
     * @param string $pseudoCardPan
     */
    public function setPseudoCardPan($pseudoCardPan)
    {
        $this->pseudocardpan = $pseudoCardPan;
    }

    /**
     * @return string
     */
    public function getPseudoCardPan()
    {
        return $this->pseudocardpan;
    }

}
