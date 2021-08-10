<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication\Plugin\Acl;

use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 */
class AclEntityAclRolePostSavePlugin extends AbstractPlugin implements AclRolePostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves `RoleTransfer.aclEntityRules` to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function postSave(RoleTransfer $roleTransfer): RoleTransfer
    {
        $this->getFacade()->saveAclEntityRules($roleTransfer->getAclEntityRules());

        return $roleTransfer;
    }
}
