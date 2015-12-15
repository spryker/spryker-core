<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Authorization;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

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
     * @varb RedirectContainer
     */
    protected $redirect;

    /**
     * @param string $cavv
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     * @param RedirectContainer $redirect
     *
     * @return void
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
