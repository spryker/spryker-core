<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Expander;

use Generated\Shared\Transfer\RolesTransfer;

interface AclRolesExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function expandAclRoles(RolesTransfer $rolesTransfer): RolesTransfer;
}
