<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin;

use Spryker\Zed\Installer\Business\Model\InstallerInterface;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin implements InstallerInterface
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
