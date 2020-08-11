<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 */
class CustomerImpersonationOauthScopeProviderPlugin extends AbstractPlugin implements OauthScopeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if `customer_impersonation` grant type is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return bool
     */
    public function accept(OauthScopeRequestTransfer $oauthScopeRequestTransfer): bool
    {
        return $oauthScopeRequestTransfer->getGrantType() === OauthCustomerConnectorConfig::GRANT_TYPE_CUSTOMER_IMPERSONATION;
    }

    /**
     * {@inheritDoc}
     * - Returns the customer impersonation scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        return $this->getFacade()->getCustomerImpersonationScopes($oauthScopeRequestTransfer);
    }
}
