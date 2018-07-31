<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface getFacade()
 * @method \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig getConfig()
 */
class MinimumOrderValueTypeInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->installMinimumOrderValueTypes();
    }
}
