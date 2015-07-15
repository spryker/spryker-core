<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;

class ThreeDSecureCheckResponseContainer extends AbstractResponseContainer
{

    /**
     * @var string
     */
    protected $acsurl;
    /**
     * @var string
     */
    protected $termurl;
    /**
     * @var string
     */
    protected $pareq;
    /**
     * @var string
     */
    protected $xid;
    /**
     * @var string
     */
    protected $md;
    /**
     * @var string
     */
    protected $pseudocardpan;
    /**
     * @var string
     */
    protected $truncatedcardpan;

    /**
     * @param string $acsurl
     */
    public function setAcsurl($acsurl)
    {
        $this->acsurl = $acsurl;
    }

    /**
     * @return string
     */
    public function getAcsurl()
    {
        return $this->acsurl;
    }

    /**
     * @param string $md
     */
    public function setMd($md)
    {
        $this->md = $md;
    }

    /**
     * @return string
     */
    public function getMd()
    {
        return $this->md;
    }

    /**
     * @param string $pareq
     */
    public function setPareq($pareq)
    {
        $this->pareq = $pareq;
    }

    /**
     * @return string
     */
    public function getPareq()
    {
        return $this->pareq;
    }

    /**
     * @param string $pseudocardpan
     */
    public function setPseudocardpan($pseudocardpan)
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudocardpan()
    {
        return $this->pseudocardpan;
    }

    /**
     * @param string $termurl
     */
    public function setTermurl($termurl)
    {
        $this->termurl = $termurl;
    }

    /**
     * @return string
     */
    public function getTermurl()
    {
        return $this->termurl;
    }

    /**
     * @param string $truncatedcardpan
     */
    public function setTruncatedcardpan($truncatedcardpan)
    {
        $this->truncatedcardpan = $truncatedcardpan;
    }

    /**
     * @return string
     */
    public function getTruncatedcardpan()
    {
        return $this->truncatedcardpan;
    }

    /**
     * @param string $xid
     */
    public function setXid($xid)
    {
        $this->xid = $xid;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }

}
