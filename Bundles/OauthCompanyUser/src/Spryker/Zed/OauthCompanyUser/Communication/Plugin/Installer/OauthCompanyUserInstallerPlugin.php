<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication\Plugin\Installer;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 * @method \Spryker\Zed\OauthCompanyUser\Communication\OauthCompanyUserCommunicationFactory getFactory()
 */
class OauthCompanyUserInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Populates database with company users oauth data.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->installCompanyUserOauthData();
    }
}
