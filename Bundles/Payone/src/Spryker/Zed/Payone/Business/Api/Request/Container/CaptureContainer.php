<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

class CaptureContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_CAPTURE;

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
     * @var BusinessContainer
     */
    protected $business;

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
     * @param Capture\BusinessContainer $business
     *
     * @return void
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
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return Invoicing\TransactionContainer|null
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

}
