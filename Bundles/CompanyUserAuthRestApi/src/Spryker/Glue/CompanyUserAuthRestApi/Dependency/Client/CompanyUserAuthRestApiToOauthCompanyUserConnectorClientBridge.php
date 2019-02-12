<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client;

class CompanyUserAuthRestApiToOauthCompanyUserConnectorClientBridge implements CompanyUserAuthRestApiToOauthCompanyUserConnectorClientInterface
{
    /**
     * @var \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorClientInterface
     */
    protected $oauthCompanyUserConnectorClient;

    /**
     * @param \Spryker\Client\OauthCompanyUserConnector\OauthCompanyUserConnectorClientInterface $oauthCompanyUserConnectorClient
     */
    public function __construct($oauthCompanyUserConnectorClient)
    {
        $this->oauthCompanyUserConnectorClient = $oauthCompanyUserConnectorClient;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->oauthCompanyUserConnectorClient->getClientId();
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->oauthCompanyUserConnectorClient->getClientSecret();
    }
}
