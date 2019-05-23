<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig
     */
    public function getModuleConfig()
    {
        /** @var \Spryker\Client\OauthCompanyUser\OauthCompanyUserConfig $config */
        $config = parent::getConfig();

        return $config;
    }
}
