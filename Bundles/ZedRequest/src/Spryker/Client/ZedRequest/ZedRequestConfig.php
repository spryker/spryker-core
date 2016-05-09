<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

class ZedRequestConfig
{

    /**
     * @var \Spryker\Shared\Config\Config
     */
    protected $config;

    /**
     * @param \Spryker\Shared\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getRawToken()
    {
        $authConfig = $this->config->get(ZedRequestConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->config->get(ZedRequestConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            return 'https://' . $this->config->get(ZedRequestConstants::HOST_SSL_ZED_API);
        } else {
            return 'http://' . $this->config->get(ZedRequestConstants::HOST_ZED_API);
        }
    }

    /**
     * @return int
     */
    public function getAuthenticationType()
    {
        if ($this->config->hasValue(ZedRequestConstants::ZED_AUTH_TYPE)) {
            return $this->config->get(ZedRequestConstants::ZED_AUTH_TYPE);
        }

        return ZedRequestConstants::AUTHENTICATE_DYNAMIC;
    }

    /**
     * @return array
     */
    public function getStaticCredential()
    {
        if ($this->config->hasValue(ZedRequestConstants::ZED_AUTH_STATIC_CREDENTIAL)) {
            return $this->config->get(ZedRequestConstants::ZED_AUTH_STATIC_CREDENTIAL);
        }

        return [];
    }

}
