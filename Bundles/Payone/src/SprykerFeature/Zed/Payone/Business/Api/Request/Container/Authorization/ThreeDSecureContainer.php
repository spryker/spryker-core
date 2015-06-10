<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;

class ThreeDSecureContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $xid;
    /**
     * @var string
     */
    protected $cavv;

    /**
     * @var string
     */
    protected $eci;
    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $cavv
     */
    public function setCavv($cavv)
    {
        $this->cavv = $cavv;
    }

    /**
     * @return string
     */
    public function getCavv()
    {
        return $this->cavv;
    }

    /**
     * @param string $eci
     */
    public function setEci($eci)
    {
        $this->eci = $eci;
    }

    /**
     * @return string
     */
    public function getEci()
    {
        return $this->eci;
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

    /**
     * @param \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
