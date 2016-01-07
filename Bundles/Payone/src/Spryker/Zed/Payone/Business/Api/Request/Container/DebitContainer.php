<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\AbstractPaymentMethodContainer;

class DebitContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_DEBIT;

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
    protected $clearingtype;

    /**
     * @var string
     */
    protected $use_customerdata;

    /**
     * @var BusinessContainer
     */
    protected $business;

    /**
     * @var AbstractPaymentMethodContainer
     */
    protected $paymentMethod;

    /**
     * @var TransactionContainer
     */
    protected $invoicing;

    /**
     * @param int $amount
     *
     * @return void
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
     * @param Debit\BusinessContainer $business
     *
     * @return void
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;
    }

    /**
     * @return Debit\BusinessContainer
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param string $clearingtype
     *
     * @return void
     */
    public function setClearingType($clearingtype)
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     *
     * @return void
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
     * @param Invoicing\TransactionContainer $invoicing
     *
     * @return void
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
     * @param Debit\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return Debit\PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param int $sequencenumber
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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

}
