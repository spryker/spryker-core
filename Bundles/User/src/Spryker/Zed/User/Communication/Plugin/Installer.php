<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * Main Installer Method
     *
     * @return void
     */
    protected function install()
    {
        $this->getFacade()->install();
    }

}
