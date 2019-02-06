<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCompanyUserConnector\Business\OauthCompanyUserConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig getConfig()
 */
class CompanyUserOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        if ($oauthUserTransfer->getGrantType() !== OauthCompanyUserConnectorConfig::GRANT_TYPE_USER) {
            return false;
        }

        if (!$oauthUserTransfer->getClientId()) {
            return false;
        }

        if ($oauthUserTransfer->getClientId() === $this->getConfig()->getClientId()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFacade()->getOauthCompanyUser($oauthUserTransfer);
    }
}
