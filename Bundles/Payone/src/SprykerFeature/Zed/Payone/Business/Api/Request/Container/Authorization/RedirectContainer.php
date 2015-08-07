<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

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
