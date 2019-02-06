<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUserConnector\Business\OauthCompanyUserConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig getConfig()
 */
class CompanyUserOauthScopeProviderPlugin extends AbstractPlugin implements OauthScopeProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return bool
     */
    public function accept(OauthScopeRequestTransfer $oauthScopeRequestTransfer): bool
    {
        if ($oauthScopeRequestTransfer->getGrantType() !== OauthCompanyUserConnectorConfig::GRANT_TYPE_USER) {
            return false;
        }

        if (!$oauthScopeRequestTransfer->getClientId()) {
            return false;
        }

        if ($oauthScopeRequestTransfer->getClientId() === $this->getConfig()->getClientId()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFacade()->getScopes($oauthScopeRequestTransfer);
    }
}
