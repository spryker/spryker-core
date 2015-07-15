<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

class CaptureContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_CAPTURE;

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
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer
     */
    protected $business;
    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @param int $amount
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
     * @param Capture\BusinessContainer $business
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;
    }

    /**
     * @return Capture\BusinessContainer
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param Invoicing\TransactionContainer $invoicing
     */
    public function setInvoicing(TransactionContainer $invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return null|Invoicing\TransactionContainer
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

}
