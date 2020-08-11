<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 */
class CustomerImpersonationOauthUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if `customer_impersonation` grant type is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        return $oauthUserTransfer->getGrantType() === OauthCustomerConnectorConfig::GRANT_TYPE_CUSTOMER_IMPERSONATION;
    }

    /**
     * {@inheritDoc}
     * - Authenticates the customer by customer reference.
     * - Returns the `OauthUserTransfer` with customer information.
     * - Runs `OauthCustomerIdentifierExpanderPluginInterface` plugins stack to expand the customer identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFacade()->getCustomerImpersonationOauthUser($oauthUserTransfer);
    }
}
