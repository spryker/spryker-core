<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUserConnector;

use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig getConfig()
 */
class OauthCompanyUserConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig
     */
    public function getModuleConfig()
    {
        /** @var \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig */
        $config = parent::getConfig();

        return $config;
    }
}
