<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer $redirect
     *
     * @return void
     */
    public function setRedirect(RedirectContainer $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}
