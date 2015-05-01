<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;


class DebitResponseContainer extends AbstractResponseContainer
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
     * @param string $settleaccount
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

}
