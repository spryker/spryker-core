<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerAccess\Communication\CustomerAccessCommunicationFactory getFactory()
 */
class CustomerAccessInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->install();
    }
}
