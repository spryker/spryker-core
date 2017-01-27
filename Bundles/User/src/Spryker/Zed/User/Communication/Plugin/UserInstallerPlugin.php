<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 */
class UserInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
