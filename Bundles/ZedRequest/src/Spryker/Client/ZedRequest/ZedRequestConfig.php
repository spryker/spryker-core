<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Shared\Config;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\Application\ApplicationConstants;

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
        $authConfig = $this->config->get(AuthConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->config->get(ApplicationConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            return 'https://' . $this->config->get(ApplicationConstants::HOST_SSL_ZED_API);
        } else {
            return 'http://' . $this->config->get(ApplicationConstants::HOST_ZED_API);
        }
    }

}
