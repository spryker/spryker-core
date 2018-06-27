<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthUserProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 */
class CustomerUserProviderPlugin extends AbstractPlugin implements OauthUserProviderPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return bool
     */
    public function accept(OauthUserTransfer $oauthUserTransfer): bool
    {
        if (!$oauthUserTransfer->getClientId()) {
            return false;
        }

        if ($oauthUserTransfer->getClientId() === $this->getConfig()->getClientId()) {
            return true;
        }

        return false;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        return $this->getFacade()->getCustomer($oauthUserTransfer);
    }
}
