<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication\Plugin\Acl;

use Generated\Shared\Transfer\RolesTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclRolesExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 * @method \Spryker\Zed\AclEntity\Communication\AclEntityCommunicationFactory getFactory()
 */
class AclRulesAclRolesExpanderPlugin extends AbstractPlugin implements AclRolesExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands `Roles` transfer object with ACL rules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $roleTransfers
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function expand(RolesTransfer $roleTransfers): RolesTransfer
    {
        return $this->getFacade()->expandAclRoles($roleTransfers);
    }
}
