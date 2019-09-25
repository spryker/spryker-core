<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\CompanyUserAccessTokenGrantType;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\Communication\OauthCompanyUserCommunicationFactory getFactory()
 */
class CompanyUserAccessTokenOauthGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthGrantTypeConfigurationProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns configuration of CompanyUser GrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer
    {
        return (new OauthGrantTypeConfigurationTransfer())
            ->setIdentifier(CompanyUserAccessTokenGrantType::COMPANY_USER_ACCESS_TOKEN_GRANT_TYPE)
            ->setFullyQualifiedClassName(CompanyUserAccessTokenGrantType::class);
    }
}
