<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingList\Communication\ShoppingListCommunicationFactory getFactory()
 */
class ShoppingListPermissionsInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->installShoppingListPermissions();
    }
}
