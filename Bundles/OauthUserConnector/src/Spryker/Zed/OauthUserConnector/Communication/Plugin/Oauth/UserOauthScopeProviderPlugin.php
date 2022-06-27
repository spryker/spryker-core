<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

/**
 * @method \Spryker\Zed\OauthUserConnector\Business\OauthUserConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig getConfig()
 */
class UserOauthScopeProviderPlugin extends AbstractPlugin implements OauthScopeProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Checks whether the grant type is {@link \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::GRANT_TYPE_PASSWORD}
     * - Checks whether the requestApplication is "GLUE_BACKEND_API_APPLICATION".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return bool
     */
    public function accept(OauthScopeRequestTransfer $oauthScopeRequestTransfer): bool
    {
        return $oauthScopeRequestTransfer->getGrantType() === OauthUserConnectorConfig::GRANT_TYPE_PASSWORD &&
            $oauthScopeRequestTransfer->getRequestApplication() === static::GLUE_BACKEND_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Makes a request to get scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFacade()->getScopes($oauthScopeRequestTransfer);
    }
}
