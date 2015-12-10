<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use SprykerEngine\Shared\Config;
use SprykerFeature\Shared\Auth\AuthConfig;
use SprykerFeature\Shared\Application\ApplicationConfig;

class ZedRequestConfig
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
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
        $authConfig = $this->config->get(AuthConfig::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->config->get(ApplicationConfig::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            return 'https://' . $this->config->get(ApplicationConfig::HOST_SSL_ZED_API);
        } else {
            return 'http://' . $this->config->get(ApplicationConfig::HOST_ZED_API);
        }
    }

}
