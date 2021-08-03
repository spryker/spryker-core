<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RolesTransfer;

/**
 * Allows to expand ACL roles with additional data.
 */
interface AclRolesExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands `Roles` transfer object with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $roleTransfers
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function expand(RolesTransfer $roleTransfers): RolesTransfer;
}
