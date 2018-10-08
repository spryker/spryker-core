<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\User\Dependency\Plugin\GroupPluginInterface;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 */
class GroupPlugin extends AbstractPlugin implements GroupPluginInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups()
    {
        return $this->getFacade()->getAllGroups();
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        return $this->getFacade()->getUserGroups($idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function addUserToGroup($idUser, $idGroup)
    {
        $this->getFacade()->addUserToGroup($idUser, $idGroup);
    }

    /**
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup)
    {
        $this->getFacade()->removeUserFromGroup($idUser, $idGroup);
    }
}
