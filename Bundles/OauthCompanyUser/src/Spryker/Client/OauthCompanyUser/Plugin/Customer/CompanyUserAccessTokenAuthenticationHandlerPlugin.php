<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser\Plugin\Customer;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Client\CustomerExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\OauthCompanyUser\OauthCompanyUserClientInterface getClient()
 */
class CompanyUserAccessTokenAuthenticationHandlerPlugin extends AbstractPlugin implements AccessTokenAuthenticationHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves customer by access token.
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerResponseTransfer
    {
        return $this->getClient()->getCustomerByAccessToken($accessToken);
    }
}
