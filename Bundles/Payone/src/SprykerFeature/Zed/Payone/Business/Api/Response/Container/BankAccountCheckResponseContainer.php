<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;

class BankAccountCheckResponseContainer extends AbstractResponseContainer
{

    /**
     * @var string
     */
    protected $bankcountry;
    /**
     * @var string
     */
    protected $bankcode;
    /**
     * @var string
     */
    protected $bankaccount;
    /**
     * @var string
     */
    protected $bankbranchcode;
    /**
     * @var string
     */
    protected $bankcheckdigit;
    /**
     * @var string
     */
    protected $iban;
    /**
     * @var string
     */
    protected $bic;

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
     * @param string $bankbranchcode
     */
    public function setBankBranchCode($bankbranchcode)
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return string
     */
    public function getBankBranchCode()
    {
        return $this->bankbranchcode;
    }

    /**
     * @param string $bankcheckdigit
     */
    public function setBankCheckDigit($bankcheckdigit)
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return string
     */
    public function getBankCheckDigit()
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param string $bankcode
     */
    public function setBankCode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string
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

}
