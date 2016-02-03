<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Dependency\Facade;

use Spryker\Zed\Acl\Business\AclFacade;

class UserToAclBridge implements UserToAclInterface
{

    /**
     * @var \Spryker\Zed\Acl\Business\AclFacade
     */
    protected $aclFacade;

    /**
     * UserToAclBridge constructor.
     *
     * @param \Spryker\Zed\Acl\Business\AclFacade $aclFacade
     */
    public function __construct($aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups()
    {
        return $this->aclFacade->getAllGroups();
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        return $this->aclFacade->getUserGroups($idUser);
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup)
    {
        return $this->aclFacade->addUserToGroup($idUser, $idGroup);
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup)
    {
        $this->aclFacade->removeUserFromGroup($idUser, $idGroup);
    }

}
