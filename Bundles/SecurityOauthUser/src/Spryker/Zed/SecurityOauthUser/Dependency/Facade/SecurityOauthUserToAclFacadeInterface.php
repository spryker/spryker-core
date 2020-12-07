<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Dependency\Facade;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;

interface SecurityOauthUserToAclFacadeInterface
{
    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup(int $idUser, int $idGroup);

    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaTransfer $groupCriteriaTransfer): ?GroupTransfer;
}
