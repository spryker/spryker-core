<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client;

class CompanyUserAuthRestApiToOauthCompanyUserClientBridge implements CompanyUserAuthRestApiToOauthCompanyUserClientInterface
{
    /**
     * @var \Spryker\Client\OauthCompanyUser\OauthCompanyUserClientInterface
     */
    protected $oauthCompanyUserClient;

    /**
     * @param \Spryker\Client\OauthCompanyUser\OauthCompanyUserClientInterface $oauthCompanyUserClient
     */
    public function __construct($oauthCompanyUserClient)
    {
        $this->oauthCompanyUserClient = $oauthCompanyUserClient;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->oauthCompanyUserClient->getClientId();
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->oauthCompanyUserClient->getClientSecret();
    }
}
