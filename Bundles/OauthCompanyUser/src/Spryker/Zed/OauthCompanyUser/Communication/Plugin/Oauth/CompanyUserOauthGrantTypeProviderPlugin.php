<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;

use League\OAuth2\Server\Grant\AbstractGrant;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Communication\OauthCompanyUserCommunicationFactory getFactory()
 */
class CompanyUserOauthGrantTypeProviderPlugin implements OauthGrantTypeProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Returns name of id company user grant type.
     *
     * @api
     *
     * @return string
     */
    public function getGrantTypeName(): string
    {
        return OauthCompanyUserConfig::GRANT_TYPE_ID_COMPANY_USER;
    }

    /**
     * {@inheritdoc}
     *  - Returns instance of id company user grant type.
     * @api
     *
     * @return \League\OAuth2\Server\Grant\AbstractGrant
     */
    public function getGrantType(): AbstractGrant
    {
        return $this->getFactory()->createIdCompanyUserGrantType();
    }
}
