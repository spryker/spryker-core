<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\ServiceProvider\AbstractHstsServiceProvider;
use Spryker\Shared\Config\Config;

/**
 * HTTP Strict Transport Security support as a ServiceProvider
 *
 * @see https://www.owasp.org/index.php/HTTP_Strict_Transport_Security
 */
class ZedHstsServiceProvider extends AbstractHstsServiceProvider implements ServiceProviderInterface
{
    /**
     * @return bool
     */
    protected function getIsHstsEnabled()
    {
        return Config::hasKey(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED)
            && Config::get(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED) === true;
    }

    /**
     * @return array
     */
    protected function getHstsConfig()
    {
        $config = [];
        if (Config::hasKey(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG)) {
            $config = Config::get(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG);
        }

        return $config;
    }
}
