<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Communication\Plugin\Installer;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthAgentConnector\Business\OauthAgentConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig getConfig()
 */
class AgentOauthScopeInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Installs agent-specific OAuth scopes.
     * - Scopes are defined in `OauthAgentConnectorConfig::getAgentScopes()`.
     * - Skips scope if it already exists in persistent storage.
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->installAgentOauthScope();
    }
}
