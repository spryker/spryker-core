<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 */
class CustomerScopeProviderPlugin extends AbstractPlugin implements OauthScopeProviderPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return bool
     */
    public function accept(OauthScopeRequestTransfer $oauthScopeRequestTransfer): bool
    {
        if (!$oauthScopeRequestTransfer->getClientId()) {
            return false;
        }

        if ($oauthScopeRequestTransfer->getClientId() === $this->getConfig()->getClientId()) {
            return true;
        }

        return false;
    }

    /**
     * @api
     *
     * {@inheritdoc}
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
