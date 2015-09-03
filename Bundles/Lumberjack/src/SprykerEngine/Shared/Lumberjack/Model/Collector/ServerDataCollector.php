<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Collector;

use SprykerFeature\Shared\Library\System;

class ServerDataCollector implements DataCollectorInterface
{

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'url'            => $this->getUrl(),
            'is_https'       => (int)$this->isSecureConnection(),
            'host_name'      => $this->getHost(),
            'user_agent'     => $this->getUserAgent(),
            'user_ip'        => $this->getRemoteAddress(),
            'request_method' => $this->getRequestMethod(),
        ];
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'unknown';
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $protocol = 'http://';

        if ($this->isSecureConnection()) {
            $protocol = 'https://';
        }
        $url = $protocol . $serverName . $requestUri;

        return $url;
    }

    /**
     * @return bool
     */
    protected function isSecureConnection()
    {
        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getHost()
    {
        return isset($_SERVER['COMPUTERNAME']) ? $_SERVER['COMPUTERNAME'] : System::getHostname();
    }

    /**
     * @return string
     */
    protected function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    }

    /**
     * @return string
     */
    protected function getHttpReferer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown';
    }

    /**
     * @return string
     */
    protected function getRemoteAddress()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    }

    protected function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';
    }

}

