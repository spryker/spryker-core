<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\IdCompanyUserGrantType;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 */
class IdCompanyUserOauthGrantConfigurationProviderPlugin extends AbstractPlugin implements OauthGrantConfigurationProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Returns configuration of IdCompanyUserGrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantConfigurationTransfer
     */
    public function getGrantConfiguration(): OauthGrantConfigurationTransfer
    {
        return (new OauthGrantConfigurationTransfer())
            ->setIdentifier(OauthCompanyUserConfig::GRANT_TYPE_ID_COMPANY_USER)
            ->setFullyQualifiedClassName(IdCompanyUserGrantType::class);
    }
}
