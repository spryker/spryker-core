<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Oauth;


use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface;

class CompanyUserOauthGrantTypeProviderPlugin implements OauthGrantTypeProviderPluginInterface
{

    /**
     * Specification:
     *  - Returns grant type name.
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
     * Specification:
     *  -
     *
     * @api
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    public function getGrantType(): GrantInterface
    {
        // TODO: Implement getGrantType() method.
    }
}
