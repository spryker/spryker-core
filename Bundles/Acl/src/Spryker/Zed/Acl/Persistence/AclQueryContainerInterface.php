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
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroupByName($name);

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroupById($id);

    /**
     * @api
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryGroup();

    /**
     * @api
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRole();

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRoleById($id);

    /**
     * @api
     *
     * @param int $idRole
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryRoleHasGroup($idRole);

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRoleByName($name);

    /**
     * @api
     *
     * @param int $idGroup
     * @param int $idRole
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRoleById($idGroup, $idRole);

    /**
     * @api
     *
     * @param int $idGroup
     * @param int $idUser
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function queryUserHasGroupById($idGroup, $idUser);

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryGroupUsers($idGroup);

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryGroupRoles($idGroup);

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleById($id);

    /**
     * @api
     *
     * @param int $roleId
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleByRoleId($roleId);

    /**
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $relationshipCollection
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryGroupRules(ObjectCollection $relationshipCollection);

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRole($idGroup);

    /**
     * @api
     *
     * @deprecated Broken and will be removed in the next major.
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function queryRuleByPathAndRoles(RolesTransfer $roles, $bundle = AclConstants::VALIDATOR_WILDCARD, $controller = AclConstants::VALIDATOR_WILDCARD, $action = AclConstants::VALIDATOR_WILDCARD);

    /**
     * @api
     *
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
     * @api
     *
     * @param int $idUser
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function queryUserGroupByIdUser($idUser);

    /**
     * @api
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUsersWithGroup();

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function queryRulesFromGroup($idGroup);
}
