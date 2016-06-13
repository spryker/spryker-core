<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Communication\Plugin\ServiceProvider\AbstractHSTSServiceProvider;
use Spryker\Shared\Config\Config;

class ZedHSTSServiceProvider extends AbstractHSTSServiceProvider implements ServiceProviderInterface
{

    /**
     * @return boolean
     */
    protected function getIsHSTSEnabled()
    {
        return Config::hasKey(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED)
            && Config::get(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED) === true;
    }

    /**
     * @return array
     */
    protected function getHSTSConfig()
    {
        $config = [];
        if (Config::hasKey(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG)) {
            $config = Config::get(ApplicationConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG);
        }
        return $config;
    }

}
