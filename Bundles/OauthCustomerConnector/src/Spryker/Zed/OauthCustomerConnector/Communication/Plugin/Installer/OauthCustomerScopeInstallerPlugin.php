<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Communication\Plugin\Installer;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig getConfig()
 */
class OauthCustomerScopeInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Installs customer-specific OAuth scopes.
     * - Scopes are defined in `OauthCustomerConnectorConfig::getCustomerScopes()`, `OauthCustomerConnectorConfig::getCustomerImpersonationScopes()`.
     * - Skips scope if it already exists in persistent storage.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->installOauthCustomerScope();
    }
}
