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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
