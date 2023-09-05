<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Communication\Plugin\Installer;

use Spryker\Zed\InstallerExtension\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\DynamicEntity\DynamicEntityConfig getConfig()
 * @method \Spryker\Zed\DynamicEntity\Communication\DynamicEntityCommunicationFactory getFactory()
 */
class DynamicEntityInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Installs Dynamic Entity data.
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->install();
    }
}
