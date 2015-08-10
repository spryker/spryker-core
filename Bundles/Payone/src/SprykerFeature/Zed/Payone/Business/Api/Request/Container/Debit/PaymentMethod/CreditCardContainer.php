<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod;

class CreditCardContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $cardpan;
    /**
     * @var string
     */
    protected $cardtype;
    /**
     * @var int
     */
    protected $cardexpiredate;
    /**
     * @var int
     */
    protected $cardcvc2;
    /**
     * @var int
     */
    protected $cardissuenumber;
    /**
     * @var string
     */
    protected $cardholder;
    /**
     * @var string
     */
    protected $pseudocardpan;

    /**
     * @param int $cardcvc2
     */
    public function setCardCvc2($cardcvc2)
    {
        $this->cardcvc2 = $cardcvc2;
    }

    /**
     * @return int
     */
    public function getCardCvc2()
    {
        return $this->cardcvc2;
    }

    /**
     * @param int $cardexpiredate
     */
    public function setCardExpireDate($cardexpiredate)
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return int
     */
    public function getCardExpireDate()
    {
        return $this->cardexpiredate;
    }

    /**
     * @param string $cardholder
     */
    public function setCardHolder($cardholder)
    {
        $this->cardholder = $cardholder;
    }

    /**
     * @return string
     */
    public function getCardHolder()
    {
        return $this->cardholder;
    }

    /**
     * @param int $cardissuenumber
     */
    public function setCardIssueNumber($cardissuenumber)
    {
        $this->cardissuenumber = $cardissuenumber;
    }

    /**
     * @return int
     */
    public function getCardIssueNumber()
    {
        return $this->cardissuenumber;
    }

    /**
     * @param string $cardpan
     */
    public function setCardPan($cardpan)
    {
        $this->cardpan = $cardpan;
    }

    /**
     * @return string
     */
    public function getCardPan()
    {
        return $this->cardpan;
    }

    /**
     * @param string $cardtype
     */
    public function setCardType($cardtype)
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardtype;
    }

    /**
     * @param string $pseudocardpan
     */
    public function setPseudoCardPan($pseudocardpan)
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudoCardPan()
    {
        return $this->pseudocardpan;
    }

}
