<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Communication\Plugin\ServiceProvider\AbstractHSTSServiceProvider;
use Spryker\Shared\Config\Config;

class YvesHSTSServiceProvider extends AbstractHSTSServiceProvider implements ServiceProviderInterface
{

    /**
     * @return boolean
     */
    protected function getIsHSTSEnabled()
    {
        return Config::hasKey(ApplicationConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED)
            && Config::get(ApplicationConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED) === true;
    }

    /**
     * @return array
     */
    protected function getHSTSConfig()
    {
        $config = [];
        if (Config::hasKey(ApplicationConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG)) {
            $config = Config::get(ApplicationConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG);
        }
        return $config;
    }

}
