<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequestExporter;

class Frontend extends AbstractRequestExporter
{
    /**
     * @var string
     */
    protected $responseUrl;

    /**
     * @var string
     */
    protected $sessionID;

    /**
     * @return string
     */
    public function getResponseUrl()
    {
        return $this->responseUrl;
    }

    /**
     * @param string $responseUrl
     */
    public function setResponseUrl($responseUrl)
    {
        $this->responseUrl = $responseUrl;
    }

    /**
     * @return string
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    /**
     * @param string $sessionID
     */
    public function setSessionID($sessionID)
    {
        $this->sessionID = $sessionID;
    }
}
