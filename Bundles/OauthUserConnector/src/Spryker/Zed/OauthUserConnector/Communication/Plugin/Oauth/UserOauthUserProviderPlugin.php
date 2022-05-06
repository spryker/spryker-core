<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

/**
 * @method \Spryker\Zed\OauthUserConnector\Business\OauthUserConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig getConfig()
 */
class UserOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks whether the requested oauth grant type equals to {@link \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig::GRANT_TYPE_PASSWORD}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        return $oauthUserTransfer->getGrantType() === OauthUserConnectorConfig::GRANT_TYPE_PASSWORD;
    }

    /**
     * {@inheritDoc}
     * - Makes a request to get oauth user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFacade()->getOauthUser($oauthUserTransfer);
    }
}
