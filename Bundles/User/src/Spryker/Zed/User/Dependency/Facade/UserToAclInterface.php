<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Dependency\Facade;

interface UserToAclInterface
{

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups();

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup);

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup);

}
