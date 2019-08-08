<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OauthCompanyUser\OauthCompanyUserFactory getFactory()
 */
class OauthCompanyUserClient extends AbstractClient implements OauthCompanyUserClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUserAccessTokenReader()
            ->getCustomerByAccessToken($accessToken);
    }
}
