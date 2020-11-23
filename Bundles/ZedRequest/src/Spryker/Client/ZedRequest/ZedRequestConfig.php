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
    public const HASH_COST = 'cost';

    /**
     * @api
     *
     * @return string
     */
    public function getRawToken()
    {
        $authConfig = $this->getConfig()->get(ZedRequestConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getHashCost()
    {
        return 4;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->getConfig()->get(ZedRequestConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            // @deprecated This is just for backward compatibility
            if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_SSL_ZED_API)) {
                return '//' . $this->getConfig()->get(ZedRequestConstants::HOST_SSL_ZED_API);
            }

            return $this->getConfig()->get(ZedRequestConstants::BASE_URL_SSL_ZED_API);
        }

        // @deprecated This is just for backward compatibility
        if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_ZED_API)) {
            return '//' . $this->getConfig()->get(ZedRequestConstants::HOST_ZED_API);
        }

        return $this->getConfig()->get(ZedRequestConstants::BASE_URL_ZED_API);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getClientConfiguration()
    {
        $clientConfiguration = [
            'timeout' => 60,
            'connect_timeout' => 1.5,
        ];

        if (Config::hasKey(ZedRequestConstants::CLIENT_OPTIONS)) {
            $customClientConfiguration = (array)$this->get(ZedRequestConstants::CLIENT_OPTIONS);
            $clientConfiguration = $customClientConfiguration + $clientConfiguration;
        }

        return $clientConfiguration;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTokenOptions()
    {
        return [
            static::HASH_COST => $this->getHashCost(),
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDevelopmentMode(): bool
    {
        return APPLICATION_ENV === 'development' || APPLICATION_ENV === 'dev.docker';
    }
}
