<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;

class RefundResponseContainer extends AbstractResponseContainer
{

    /**
     * @var int
     */
    protected $txid;
    /**
     * @var string
     */
    protected $protect_result_avs;

    /**
     * @param string $protect_result_avs
     */
    public function setProtectResultAvs($protect_result_avs)
    {
        $this->protect_result_avs = $protect_result_avs;
    }

    /**
     * @return string
     */
    public function getProtectResultAvs()
    {
        return $this->protect_result_avs;
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
