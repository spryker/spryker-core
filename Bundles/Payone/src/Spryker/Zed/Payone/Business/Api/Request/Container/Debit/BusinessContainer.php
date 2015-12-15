<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Debit;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class BusinessContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $settleaccount;

    /**
     * @var string
     */
    protected $transactiontype;

    /**
     * @var string
     */
    protected $booking_date;

    /**
     * @var string
     */
    protected $document_date;

    /**
     * @param string $booking_date
     *
     * @return void
     */
    public function setBookingDate($booking_date)
    {
        $this->booking_date = $booking_date;
    }

    /**
     * @return string
     */
    public function getBookingDate()
    {
        return $this->booking_date;
    }

    /**
     * @param string $document_date
     *
     * @return void
     */
    public function setDocumentDate($document_date)
    {
        $this->document_date = $document_date;
    }

    /**
     * @return string
     */
    public function getDocumentDate()
    {
        return $this->document_date;
    }

    /**
     * @param string $settleaccount
     *
     * @return void
     */
    public function setSettleAccount($settleaccount)
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleAccount()
    {
        return $this->settleaccount;
    }

    /**
     * @param string $transactiontype
     *
     * @return void
     */
    public function setTransactionType($transactiontype)
    {
        $this->transactiontype = $transactiontype;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactiontype;
    }

}
