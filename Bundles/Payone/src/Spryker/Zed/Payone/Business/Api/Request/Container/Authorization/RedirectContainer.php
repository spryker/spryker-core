<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Authorization;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class RedirectContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $successurl;

    /**
     * @var string
     */
    protected $errorurl;

    /**
     * @var string
     */
    protected $backurl;

    /**
     * @param string $backurl
     *
     * @return void
     */
    public function setBackUrl($backurl)
    {
        $this->backurl = $backurl;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->backurl;
    }

    /**
     * @param string $errorurl
     *
     * @return void
     */
    public function setErrorUrl($errorurl)
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        return $this->errorurl;
    }

    /**
     * @param string $successurl
     *
     * @return void
     */
    public function setSuccessUrl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->successurl;
    }

}
