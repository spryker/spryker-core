<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Installer\Business\Model\InstallerInterface;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin implements InstallerInterface
{

    /**
     * Main Installer Method
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
