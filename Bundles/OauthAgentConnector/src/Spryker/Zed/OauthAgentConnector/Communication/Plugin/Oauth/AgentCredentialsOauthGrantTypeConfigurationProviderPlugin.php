<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oauth\Business\Model\League\Grant\PasswordGrantType;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig getConfig()
 * @method \Spryker\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacadeInterface getFacade()
 */
class AgentCredentialsOauthGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthGrantTypeConfigurationProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns configuration of Password GrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer
    {
        return (new OauthGrantTypeConfigurationTransfer())
            ->setIdentifier(OauthAgentConnectorConfig::GRANT_TYPE_AGENT_CREDENTIALS)
            ->setFullyQualifiedClassName(AgetntCredentialsGrantType::class);
    }
}
