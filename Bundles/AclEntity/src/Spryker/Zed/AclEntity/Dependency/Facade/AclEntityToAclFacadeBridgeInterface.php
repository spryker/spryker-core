<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Dependency\Facade;

use Generated\Shared\Transfer\RolesTransfer;

interface AclEntityToAclFacadeBridgeInterface
{
    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getUserRoles(int $idUser): RolesTransfer;
}
