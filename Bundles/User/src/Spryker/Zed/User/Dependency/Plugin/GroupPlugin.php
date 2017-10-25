<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Dependency\Plugin;

use Generated\Shared\Transfer\GroupsTransfer;

class GroupPlugin implements GroupPluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups()
    {
        return new GroupsTransfer();
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        return new GroupsTransfer();
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function addUserToGroup($idUser, $idGroup)
    {
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup)
    {
    }
}
