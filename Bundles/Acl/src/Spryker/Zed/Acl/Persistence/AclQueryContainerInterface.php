<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Generated\Shared\Transfer\RolesTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Acl\AclConstants;

interface AclQueryContainerInterface
{

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroupByName($name);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroupById($id);

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroup();

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRole();

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryRoleById($id);

    /**
     * @param int $idRole
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryRoleHasGroup($idRole);

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRoleByName($name);

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRoleById($idGroup, $idRole);

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return \Orm\Zed\Acl\Persistence\Base\SpyAclUserHasGroupQuery
     */
    public function queryUserHasGroupById($idGroup, $idUser);

    /**
     * @param int $idGroup
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryGroupUsers($idGroup);

    /**
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryGroupRoles($idGroup);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleById($id);

    /**
     * @param int $roleId
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleByRoleId($roleId);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $relationshipCollection
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryGroupRules(ObjectCollection $relationshipCollection);

    /**
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRole($idGroup);

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleByPathAndRoles(RolesTransfer $roles, $bundle = AclConstants::VALIDATOR_WILDCARD, $controller = AclConstants::VALIDATOR_WILDCARD, $action = AclConstants::VALIDATOR_WILDCARD);

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param int $type
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleByPathAndRole($idAclRole, $bundle, $controller, $action, $type);

    /**
     * @param int $idUser
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryUserGroupByIdUser($idUser);

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUsersWithGroup();

    /**
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRulesFromGroup($idGroup);

}
