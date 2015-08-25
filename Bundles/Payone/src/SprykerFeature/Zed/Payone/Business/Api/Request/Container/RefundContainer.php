<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

class RefundContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_REFUND;

    /**
     * @var string
     */
    protected $txid;

    /**
     * @var int
     */
    protected $sequencenumber;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $narrative_text;

    /**
     * @var string
     */
    protected $use_customerdata;

    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer
     */
    protected $paymentMethod;

    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @var string
     */
    protected $bankcountry;

    /**
     * @var string
     */
    protected $bankaccount;

    /**
     * @var string
     */
    protected $bankcode;

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
     * @param int $amount
     * Amount of refund (in smallest currency unit! e.g.
     * cent). The amount must be less than or equal to
     * the amount of the corresponding booking.
     * (Always provide a negative amount)
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $narrative_text
     */
    public function setNarrativeText($narrative_text)
    {
        $this->narrative_text = $narrative_text;
    }

    /**
     * @return string
     */
    public function getNarrativeText()
    {
        return $this->narrative_text;
    }

    /**
     * @param Invoicing\TransactionContainer $invoicing
     */
    public function setInvoicing(TransactionContainer $invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return Invoicing\TransactionContainer
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

    /**
     * @param Refund\PaymentMethod\BankAccountContainer $paymentMethod
     */
    public function setPaymentMethod(BankAccountContainer $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return Refund\PaymentMethod\BankAccountContainer
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param int $sequencenumber
     */
    public function setSequenceNumber($sequencenumber)
    {
        $this->sequencenumber = $sequencenumber;
    }

    /**
     * @return int
     */
    public function getSequenceNumber()
    {
        return $this->sequencenumber;
    }

    /**
     * @param string $txid
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;
    }

    /**
     * @return string
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * @param string $use_customerdata
     */
    public function setUseCustomerData($use_customerdata)
    {
        $this->use_customerdata = $use_customerdata;
    }

    /**
     * @return string
     */
    public function getUseCustomerData()
    {
        return $this->use_customerdata;
    }

    /**
     * @return string
     */
    public function getBankcountry()
    {
        return $this->bankcountry;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankcountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankaccount()
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankaccount
     */
    public function setBankaccount($bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankcode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcode
     */
    public function setBankcode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string
     */
    public function getBankbranchcode()
    {
        return $this->bankbranchcode;
    }

    /**
     * @param string $bankbranchcode
     */
    public function setBankbranchcode($bankbranchcode)
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return string
     */
    public function getBankcheckdigit()
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param string $bankcheckdigit
     */
    public function setBankcheckdigit($bankcheckdigit)
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
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
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

}
