<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;

class CaptureResponseContainer extends AbstractResponseContainer
{

    /**
     * @var int
     */
    protected $txid;
    /**
     * @var string
     */
    protected $settleaccount;
    /**
     * @var string
     */
    protected $clearing_bankaccountholder;
    /**
     * @var string
     */
    protected $clearing_bankcountry;
    /**
     * @var string
     */
    protected $clearing_bankaccount;
    /**
     * @var string
     */
    protected $clearing_bankcode;
    /**
     * @var string
     */
    protected $clearing_bankiban;
    /**
     * @var string
     */
    protected $clearing_bankbic;
    /**
     * @var string
     */
    protected $clearing_bankcity;
    /**
     * @var string
     */
    protected $clearing_bankname;
    /**
     * @var string
     */
    protected $clearing_legalnote;
    /**
     * (YYYYMMDD)
     *
     * @var string
     */
    protected $clearing_duedate;
    /**
     * @var string
     */
    protected $clearing_reference;
    /**
     * @var string
     */
    protected $clearing_instructionnote;
    /**
     * @var string
     */
    protected $mandate_identification;
    /**
     * @var string
     */
    protected $creditor_identifier;
    /**
     * @var string
     */
    protected $creditor_name;
    /**
     * @var string
     */
    protected $creditor_street;
    /**
     * @var string
     */
    protected $creditor_zip;
    /**
     * @var string
     */
    protected $creditor_city;
    /**
     * @var string
     */
    protected $creditor_country;
    /**
     * @var string
     */
    protected $creditor_email;
    /**
     * @var string
     */
    protected $clearing_date;
    /**
     * @var string
     */
    protected $clearing_amount;

    /**
     * @param string $clearing_bankaccount
     *
     * @return void
     */
    public function setClearingBankaccount($clearing_bankaccount)
    {
        $this->clearing_bankaccount = $clearing_bankaccount;
    }

    /**
     * @return string
     */
    public function getClearingBankaccount()
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearing_bankaccountholder
     *
     * @return void
     */
    public function setClearingBankaccountholder($clearing_bankaccountholder)
    {
        $this->clearing_bankaccountholder = $clearing_bankaccountholder;
    }

    /**
     * @return string
     */
    public function getClearingBankaccountholder()
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearing_bankbic
     *
     * @return void
     */
    public function setClearingBankbic($clearing_bankbic)
    {
        $this->clearing_bankbic = $clearing_bankbic;
    }

    /**
     * @return string
     */
    public function getClearingBankbic()
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearing_bankcity
     *
     * @return void
     */
    public function setClearingBankcity($clearing_bankcity)
    {
        $this->clearing_bankcity = $clearing_bankcity;
    }

    /**
     * @return string
     */
    public function getClearingBankcity()
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearing_bankcode
     *
     * @return void
     */
    public function setClearingBankcode($clearing_bankcode)
    {
        $this->clearing_bankcode = $clearing_bankcode;
    }

    /**
     * @return string
     */
    public function getClearingBankcode()
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearing_bankcountry
     *
     * @return void
     */
    public function setClearingBankcountry($clearing_bankcountry)
    {
        $this->clearing_bankcountry = $clearing_bankcountry;
    }

    /**
     * @return string
     */
    public function getClearingBankcountry()
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearing_bankiban
     *
     * @return void
     */
    public function setClearingBankiban($clearing_bankiban)
    {
        $this->clearing_bankiban = $clearing_bankiban;
    }

    /**
     * @return string
     */
    public function getClearingBankiban()
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearing_bankname
     *
     * @return void
     */
    public function setClearingBankname($clearing_bankname)
    {
        $this->clearing_bankname = $clearing_bankname;
    }

    /**
     * @return string
     */
    public function getClearingBankname()
    {
        return $this->clearing_bankname;
    }

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleaccount($settleaccount)
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleaccount()
    {
        return $this->settleaccount;
    }

    /**
     * @param int $txid
     *
     * @return void
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;
    }

    /**
     * @return int
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * @param string $clearing_duedate
     *
     * @return void
     */
    public function setClearingDuedate($clearing_duedate)
    {
        $this->clearing_duedate = $clearing_duedate;
    }

    /**
     * @return string
     */
    public function getClearingDuedate()
    {
        return $this->clearing_duedate;
    }

    /**
     * @param string $clearing_instructionnote
     *
     * @return void
     */
    public function setClearingInstructionnote($clearing_instructionnote)
    {
        $this->clearing_instructionnote = $clearing_instructionnote;
    }

    /**
     * @return string
     */
    public function getClearingInstructionnote()
    {
        return $this->clearing_instructionnote;
    }

    /**
     * @param string $clearing_legalnote
     *
     * @return void
     */
    public function setClearingLegalnote($clearing_legalnote)
    {
        $this->clearing_legalnote = $clearing_legalnote;
    }

    /**
     * @return string
     */
    public function getClearingLegalnote()
    {
        return $this->clearing_legalnote;
    }

    /**
     * @param string $clearing_reference
     *
     * @return void
     */
    public function setClearingReference($clearing_reference)
    {
        $this->clearing_reference = $clearing_reference;
    }

    /**
     * @return string
     */
    public function getClearingReference()
    {
        return $this->clearing_reference;
    }

    /**
     * @param string $creditorCity
     *
     * @return void
     */
    public function setCreditorCity($creditorCity)
    {
        $this->creditor_city = $creditorCity;
    }

    /**
     * @return string
     */
    public function getCreditorCity()
    {
        return $this->creditor_city;
    }

    /**
     * @param string $creditorCountry
     *
     * @return void
     */
    public function setCreditorCountry($creditorCountry)
    {
        $this->creditor_country = $creditorCountry;
    }

    /**
     * @return string
     */
    public function getCreditorCountry()
    {
        return $this->creditor_country;
    }

    /**
     * @param string $creditorEmail
     *
     * @return void
     */
    public function setCreditorEmail($creditorEmail)
    {
        $this->creditor_email = $creditorEmail;
    }

    /**
     * @return string
     */
    public function getCreditorEmail()
    {
        return $this->creditor_email;
    }

    /**
     * @param string $creditorIdentifier
     *
     * @return void
     */
    public function setCreditorIdentifier($creditorIdentifier)
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier()
    {
        return $this->creditor_identifier;
    }

    /**
     * @param string $creditorName
     *
     * @return void
     */
    public function setCreditorName($creditorName)
    {
        $this->creditor_name = $creditorName;
    }

    /**
     * @return string
     */
    public function getCreditorName()
    {
        return $this->creditor_name;
    }

    /**
     * @param string $creditorStreet
     *
     * @return void
     */
    public function setCreditorStreet($creditorStreet)
    {
        $this->creditor_street = $creditorStreet;
    }

    /**
     * @return string
     */
    public function getCreditorStreet()
    {
        return $this->creditor_street;
    }

    /**
     * @param string $creditorZip
     *
     * @return void
     */
    public function setCreditorZip($creditorZip)
    {
        $this->creditor_zip = $creditorZip;
    }

    /**
     * @return string
     */
    public function getCreditorZip()
    {
        return $this->creditor_zip;
    }

    /**
     * @param string $mandateIdentification
     *
     * @return void
     */
    public function setMandateIdentification($mandateIdentification)
    {
        $this->mandate_identification = $mandateIdentification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification()
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $clearingAmount
     *
     * @return void
     */
    public function setClearingAmount($clearingAmount)
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return string
     */
    public function getClearingAmount()
    {
        return $this->clearing_amount;
    }

    /**
     * @param string $clearingDate
     *
     * @return void
     */
    public function setClearingDate($clearingDate)
    {
        $this->clearing_date = $clearingDate;
    }

    /**
     * @return string
     */
    public function getClearingDate()
    {
        return $this->clearing_date;
    }

}
