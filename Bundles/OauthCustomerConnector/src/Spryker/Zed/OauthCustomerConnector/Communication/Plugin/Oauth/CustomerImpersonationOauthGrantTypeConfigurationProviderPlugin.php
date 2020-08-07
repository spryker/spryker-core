<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnector\Business\League\Grant\CustomerImpersonationGrantType;
use Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeConfigurationProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 */
class CustomerImpersonationOauthGrantTypeConfigurationProviderPlugin extends AbstractPlugin implements OauthGrantTypeConfigurationProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns configuration of `customer_impersonation` GrantType.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer
     */
    public function getGrantTypeConfiguration(): OauthGrantTypeConfigurationTransfer
    {
        return (new OauthGrantTypeConfigurationTransfer())
            ->setIdentifier(OauthCustomerConnectorConfig::GRANT_TYPE_CUSTOMER_IMPERSONATION)
            ->setFullyQualifiedClassName(CustomerImpersonationGrantType::class);
    }
}
