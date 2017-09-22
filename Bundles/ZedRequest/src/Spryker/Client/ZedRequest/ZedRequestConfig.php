<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

class ZedRequestConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getRawToken()
    {
        $authConfig = $this->getConfig()->get(ZedRequestConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @return int
     */
    public function getHashCost()
    {
        return 4;
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->getConfig()->get(ZedRequestConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            // @deprecated This is just for backward compatibility
            if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_SSL_ZED_API)) {
                return 'https://' . $this->getConfig()->get(ZedRequestConstants::HOST_SSL_ZED_API);
            }

            return $this->getConfig()->get(ZedRequestConstants::BASE_URL_SSL_ZED_API);
        }

        // @deprecated This is just for backward compatibility
        if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_ZED_API)) {
            return 'http://' . $this->getConfig()->get(ZedRequestConstants::HOST_ZED_API);
        }

        return $this->getConfig()->get(ZedRequestConstants::BASE_URL_ZED_API);
    }

    /**
     * @return bool
     */
    public function isAuthenticationEnabled()
    {
        return $this->getConfig()->get(ZedRequestConstants::AUTH_ZED_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getClientConfiguration()
    {
        $clientConfiguration = [
            'timeout' => 60,
            'connect_timeout' => 1.5,
        ];

        if (Config::hasKey(ZedRequestConstants::CLIENT_CONFIG)) {
            $customClientConfiguration = $this->get(ZedRequestConstants::CLIENT_CONFIG);
            $clientConfiguration = array_merge($clientConfiguration, $customClientConfiguration);
        }

        return $clientConfiguration;
    }

    /**
     * @return array
     */
    public function getTokenOptions()
    {
        return [
            'cost' => $this->getHashCost(),
        ];
    }
}
