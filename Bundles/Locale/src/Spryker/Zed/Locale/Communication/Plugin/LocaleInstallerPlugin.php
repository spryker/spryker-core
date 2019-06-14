<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Locale\Communication\LocaleCommunicationFactory getFactory()
 * @method \Spryker\Zed\Locale\Business\LocaleFacadeInterface getFacade()
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface getQueryContainer()
 */
class LocaleInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }
}
