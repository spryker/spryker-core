<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Shared\Payone\PayoneApiConstants;

class CreditCardCheckContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CREDITCARDCHECK;

    /**
     * @var int
     */
    protected $aid;

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
    protected $storecarddata;

    /**
     * @var string
     */
    protected $language;

    /**
     * @param int $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

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
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $storecarddata
     */
    public function setStoreCardData($storecarddata)
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string
     */
    public function getStoreCardData()
    {
        return $this->storecarddata;
    }

}
