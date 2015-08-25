<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class PrepaymentContainer extends AbstractPaymentMethodContainer
{

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
     * @var
     */
    protected $clearing_bankname;

    /**
     * @param string $clearingBankAccountHolder
     */
    public function setClearingBankAccountHolder($clearingBankAccountHolder)
    {
        $this->clearing_bankaccountholder = $clearingBankAccountHolder;
    }

    /**
     * @return string
     */
    public function getClearingBankAccountHolder()
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearingBankCountry
     */
    public function setClearingBankCountry($clearingBankCountry)
    {
        $this->clearing_bankcountry = $clearingBankCountry;
    }

    /**
     * @return string
     */
    public function getClearingBankCountry()
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearingBankAccount
     */
    public function setClearingBankAccount($clearingBankAccount)
    {
        $this->clearing_bankaccount = $clearingBankAccount;
    }

    /**
     * @return string
     */
    public function getClearingBankAccount()
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearingBankCode
     */
    public function setClearingBankCode($clearingBankCode)
    {
        $this->clearing_bankcode = $clearingBankCode;
    }

    /**
     * @return string
     */
    public function getClearingBankCode()
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearingBankIban
     */
    public function setClearingBankIban($clearingBankIban)
    {
        $this->clearing_bankiban = $clearingBankIban;
    }

    /**
     * @return string
     */
    public function getClearingBankIban()
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearingBankBic
     */
    public function setClearingBankBic($clearingBankBic)
    {
        $this->clearing_bankbic = $clearingBankBic;
    }

    /**
     * @return string
     */
    public function getClearingBankBic()
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearingBankCity
     */
    public function setClearingBankCity($clearingBankCity)
    {
        $this->clearing_bankcity = $clearingBankCity;
    }

    /**
     * @return string
     */
    public function getClearingBankCity()
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearingBankName
     */
    public function setClearingBankName($clearingBankName)
    {
        $this->clearing_bankname = $clearingBankName;
    }

    /**
     * @return string
     */
    public function getClearingBankName()
    {
        return $this->clearing_bankname;
    }

}
