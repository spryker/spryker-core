<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

use Spryker\Service\UtilNetwork\Model\Host;

/**
 * @deprecated Use Log bundle instead
 */
class ServerDataCollector extends AbstractDataCollector
{
    const TYPE = 'server';

    const FIELD_URL = 'url';

    const FIELD_IS_HTTPS = 'is_https';

    const FIELD_HOST_NAME = 'host_name';

    const FIELD_USER_AGENT = 'user_agent';

    const FIELD_USER_IP = 'user_ip';

    const FIELD_REQUEST_METHOD = 'request_method';

    const FIELD_REFERRER = 'referrer';

    /**
     * @return array
     */
    public function getData()
    {
        return [
            self::FIELD_URL => $this->getUrl(),
            self::FIELD_IS_HTTPS => (int)$this->isSecureConnection(),
            self::FIELD_HOST_NAME => $this->getHost(),
            self::FIELD_USER_AGENT => $this->getUserAgent(),
            self::FIELD_USER_IP => $this->getRemoteAddress(),
            self::FIELD_REQUEST_METHOD => $this->getRequestMethod(),
            self::FIELD_REFERRER => $this->getHttpReferrer(),
        ];
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
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
        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
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
        $utilNetworkHost = new Host();

        return isset($_SERVER['COMPUTERNAME']) ? $_SERVER['COMPUTERNAME'] : $utilNetworkHost->getHostname();
    }

    /**
     * @return string|null
     */
    protected function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * @return string|null
     */
    protected function getRemoteAddress()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * @return string
     */
    protected function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';
    }

    /**
     * @return string|null
     */
    protected function getHttpReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }
}
