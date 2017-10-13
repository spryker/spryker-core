<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 */
class PriceInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }
}
