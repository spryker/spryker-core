<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Communication\Plugin\Installer;

use Spryker\Zed\InstallerExtension\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 * @method \Spryker\Zed\OauthWarehouse\Communication\OauthWarehouseCommunicationFactory getFactory()
 */
class OauthWarehouseInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Installs warehouse OAuth scope data.
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->installWarehouseOauthData();
    }
}
