<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCompanyUser;

use Generated\Shared\Transfer\CustomerTransfer;
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
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getClientSecret();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getClientId();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerTransfer
    {
        return $this->getFactory()
            ->createCompanyUserAccessTokenReader()
            ->getCustomerByAccessToken($accessToken);
    }
}
