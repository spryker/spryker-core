<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod;

class BankAccountContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $bankcountry;
    /**
     * @var string
     */
    protected $bankaccount;
    /**
     * @var int
     */
    protected $bankcode;
    /**
     * @var int
     */
    protected $bankbranchcode;
    /**
     * @var int
     */
    protected $bankcheckdigit;
    /**
     * @var string
     */
    protected $bankaccountholder;
    /**
     * @var string
     */
    protected $iban;
    /**
     * @var string
     */
    protected $bic;
    /**
     * @var string
     */
    protected $mandate_identification;

    /**
     * @param string $bankaccount
     */
    public function setBankAccount($bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankaccountholder
     */
    public function setBankAccountHolder($bankaccountholder)
    {
        $this->bankaccountholder = $bankaccountholder;
    }

    /**
     * @return string
     */
    public function getBankAccountHolder()
    {
        return $this->bankaccountholder;
    }

    /**
     * @param int $bankbranchcode
     */
    public function setBankBranchCode($bankbranchcode)
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return int
     */
    public function getBankBranchCode()
    {
        return $this->bankbranchcode;
    }

    /**
     * @param int $bankcheckdigit
     */
    public function setBankCheckDigit($bankcheckdigit)
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return int
     */
    public function getBankCheckDigit()
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param int $bankcode
     */
    public function setBankCode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return int
     */
    public function getBankCode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankCountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankCountry()
    {
        return $this->bankcountry;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $mandateIdentification
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

}
