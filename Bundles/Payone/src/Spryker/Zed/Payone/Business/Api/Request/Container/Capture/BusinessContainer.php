<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Capture;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class BusinessContainer extends AbstractContainer
{

    /**
     * (YYYYMMDD)
     *
     * @var string
     */
    protected $document_date;

    /**
     * (YYYYMMDD)
     *
     * @var string
     */
    protected $booking_date;

    /**
     * (Unixtimestamp)
     *
     * @var string
     */
    protected $due_time;

    /**
     * ENUM
     *
     * @var string
     */
    protected $settleaccount;

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
     * @param string $due_time
     *
     * @return void
     */
    public function setDueTime($due_time)
    {
        $this->due_time = $due_time;
    }

    /**
     * @return string
     */
    public function getDueTime()
    {
        return $this->due_time;
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

}
