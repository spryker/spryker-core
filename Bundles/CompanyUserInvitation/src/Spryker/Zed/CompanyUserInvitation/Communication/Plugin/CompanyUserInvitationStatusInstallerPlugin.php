<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserInvitation\Communication\CompanyUserInvitationCommunicationFactory getFactory()
 */
class CompanyUserInvitationStatusInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }
}
