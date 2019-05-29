<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Communication\Plugin\Installer;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Oauth\Business\OauthFacadeInterface getFacade()
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 */
class OauthClientInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Populates database with oauth client data.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->installOauthClient();
    }
}
