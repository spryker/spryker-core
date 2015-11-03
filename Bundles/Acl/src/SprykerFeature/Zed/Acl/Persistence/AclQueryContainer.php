<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Persistence;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\AclPersistence;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Acl\AclConfig;
use Orm\Zed\Acl\Persistence\Base\SpyAclUserHasGroupQuery;
use Orm\Zed\Acl\Persistence\Map\SpyAclGroupsHasRolesTableMap;
use Orm\Zed\Acl\Persistence\Map\SpyAclGroupTableMap;
use Orm\Zed\Acl\Persistence\Map\SpyAclRoleTableMap;
use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Orm\Zed\Acl\Persistence\Map\SpyAclUserHasGroupTableMap;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery;
use Orm\Zed\Acl\Persistence\SpyAclRuleQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Orm\Zed\User\Persistence\SpyUserQuery;

/**
 * @method AclDependencyContainer getDependencyContainer()
 * @method AclPersistence getFactory()
 */
class AclQueryContainer extends AbstractQueryContainer
{

    const ROLE_NAME = 'role_name';
    const TYPE = 'type';
    const BUNDLE = 'bundle';
    const CONTROLLER = 'controller';
    const ACTION = 'action';
    const HAS_ROLE = 'has_role';
    const SPY_ACL_GROUPS_HAS_ROLES = 'SpyAclGroupsHasRoles';
    const GROUP_NAME = 'group_name';
    const ID_ACL_GROUP = 'id_acl_group';
    const GROUP_JOIN = 'groupJoin';

    /**
     * @param string $name
     *
     * @return SpyAclGroupQuery
     */
    public function queryGroupByName($name)
    {
        $query = $this->queryGroup();

        $query->filterByName($name);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyAclGroupQuery
     */
    public function queryGroupById($id)
    {
        $query = $this->queryGroup();

        $query->filterByIdAclGroup($id);

        return $query;
    }

    /**
     * @return SpyAclGroupQuery
     */
    public function queryGroup()
    {
        return $this->getDependencyContainer()->createGroupQuery();
    }

    /**
     * @return SpyAclRoleQuery
     */
    public function queryRole()
    {
        return $this->getDependencyContainer()->createRoleQuery();
    }

    /**
     * @param int $id
     *
     * @return SpyAclGroupQuery
     */
    public function queryRoleById($id)
    {
        $query = $this->getDependencyContainer()->createRoleQuery();

        $query->filterByIdAclRole($id);

        return $query;
    }

    /**
     * @param int $idRole
     *
     * @return SpyAclGroupsHasRolesQuery
     */
    public function queryRoleHasGroup($idRole)
    {
        $query = $this->getDependencyContainer()->createGroupHasRoleQuery();
        $query->filterByFkAclRole($idRole);

        return $query;
    }

    /**
     * @param string $name
     *
     * @return SpyAclRoleQuery
     */
    public function queryRoleByName($name)
    {
        $query = $this->getDependencyContainer()->createRoleQuery();

        $query->filterByName($name);

        return $query;
    }

    /**
     * @param int $idGroup
     * @param int $idRole
     *
     * @return SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRoleById($idGroup, $idRole)
    {
        $query = $this->getDependencyContainer()->createGroupHasRoleQuery();

        $query->filterByFkAclGroup($idGroup)
            ->filterByFkAclRole($idRole);

        return $query;
    }

    /**
     * @param int $idGroup
     * @param int $idUser
     *
     * @return SpyAclUserHasGroupQuery
     */
    public function queryUserHasGroupById($idGroup, $idUser)
    {
        $query = $this->getDependencyContainer()->createUserHasRoleQuery();

        $query->filterByFkAclGroup($idGroup)
              ->filterByFkUser($idUser);

        return $query;
    }

    /**
     * @param int $idGroup
     *
     * @return SpyUserQuery
     */
    public function queryGroupUsers($idGroup)
    {
        $query = $this->getDependencyContainer()->createUserQuery();

        $join = new Join();

        $join->addCondition(
            SpyUserTableMap::COL_ID_USER_USER,
            SpyAclUserHasGroupTableMap::COL_FK_USER
        );

        $query->addJoinObject($join, self::GROUP_JOIN);

        $condition = sprintf('%s = %s', SpyAclUserHasGroupTableMap::COL_FK_ACL_GROUP, $idGroup);
        $query->addJoinCondition(
            self::GROUP_JOIN,
            $condition
        );

        return $query;
    }

    /**
     * @param int $idGroup
     *
     * @return SpyAclRoleQuery
     */
    public function queryGroupRoles($idGroup)
    {
        $query = $this->getDependencyContainer()->createRoleQuery();

        $query->useSpyAclGroupsHasRolesQuery()
            ->filterByFkAclGroup($idGroup)
            ->endUse();

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyAclRuleQuery
     */
    public function queryRuleById($id)
    {
        $query = $this->getDependencyContainer()->createRuleQuery();

        $query->filterByIdAclRule($id);

        return $query;
    }

    /**
     * @param $roleId
     *
     * @return SpyAclRuleQuery
     */
    public function queryRuleByRoleId($roleId)
    {
        $query = $this->getDependencyContainer()->createRuleQuery();
        $query->filterByFkAclRole($roleId);

        return $query;
    }

    /**
     * @param ObjectCollection $relationshipCollection
     *
     * @return SpyAclRuleQuery
     */
    public function queryGroupRules(ObjectCollection $relationshipCollection)
    {
        $query = $this->getDependencyContainer()->createRuleQuery();
        $query->useAclRoleQuery()->filterBySpyAclGroupsHasRoles($relationshipCollection)->endUse();

        return $query;
    }

    /**
     * @param int $idGroup
     *
     * @return SpyAclGroupsHasRolesQuery
     */
    public function queryGroupHasRole($idGroup)
    {
        $query = $this->getDependencyContainer()->createGroupHasRoleQuery();
        $query->filterByFkAclGroup($idGroup);

        return $query;
    }

    /**
     * @param RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return SpyAclRuleQuery
     */
    public function queryRuleByPathAndRoles(
        RolesTransfer $roles,
        $bundle = AclConfig::VALIDATOR_WILDCARD,
        $controller = AclConfig::VALIDATOR_WILDCARD,
        $action = AclConfig::VALIDATOR_WILDCARD
    ) {
        $query = $this->getDependencyContainer()->createRuleQuery();

        if ($bundle !== AclConfig::VALIDATOR_WILDCARD) {
            $query->filterByBundle($bundle);
        }

        if ($controller !== AclConfig::VALIDATOR_WILDCARD) {
            $query->filterByController($controller);
        }

        if ($action !== AclConfig::VALIDATOR_WILDCARD) {
            $query->filterByAction($action);
        }

        $inRoles = [];
        foreach ($roles as $role) {
            $inRoles[] = $role->getIdAclRole();
        }

        $query->filterByFkAclRole($inRoles, Criteria::IN);

        return $query;
    }

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param int $type
     *
     * @throws PropelException
     *
     * @return SpyAclRuleQuery
     */
    public function queryRuleByPathAndRole($idAclRole, $bundle, $controller, $action, $type)
    {
        $query = $this->getDependencyContainer()->createRuleQuery();
        $query->filterByFkAclRole($idAclRole, Criteria::EQUAL)
            ->filterByBundle($bundle, Criteria::EQUAL)
            ->filterByController($controller, Criteria::EQUAL)
            ->filterByAction($action, Criteria::EQUAL)
            ->filterByType($type, Criteria::EQUAL)
        ;

        return $query;
    }

    /**
     * @param int $idUser
     *
     * @return SpyAclGroupQuery
     */
    public function queryUserGroupByIdUser($idUser)
    {
        $query = $this->getDependencyContainer()->createGroupQuery();
        $query->useSpyAclUserHasGroupQuery()
            ->filterByFkUser($idUser)
            ->endUse();

        return $query;
    }

    /**
     * @return SpyUserQuery
     */
    public function queryUsersWithGroup()
    {
        $query = $this->getDependencyContainer()->createUserQuery();

        $query->addJoin(
            SpyUserTableMap::COL_ID_USER,
            SpyAclUserHasGroupTableMap::COL_FK_USER,
            Criteria::LEFT_JOIN
        );

        $query->addJoin(
            SpyAclUserHasGroupTableMap::COL_FK_ACL_GROUP,
            SpyAclGroupTableMap::COL_ID_ACL_GROUP,
            Criteria::LEFT_JOIN
        );

        $query->withColumn(SpyAclGroupTableMap::COL_NAME, self::GROUP_NAME);
        $query->withColumn(SpyAclGroupTableMap::COL_ID_ACL_GROUP, self::ID_ACL_GROUP);

        return $query;
    }

    /**
     * @param int $idGroup
     *
     * @return SpyAclRoleQuery
     */
    public function queryRulesFromGroup($idGroup)
    {
        $query = $this->getDependencyContainer()->createRoleQuery();
        $query->joinAclRule();
        $query->leftJoinSpyAclGroupsHasRoles();

        $condition = sprintf('%s = %s', SpyAclGroupsHasRolesTableMap::COL_FK_ACL_GROUP, $idGroup);
        $query->addJoinCondition(
            self::SPY_ACL_GROUPS_HAS_ROLES,
            $condition
        );

        $hasRole = sprintf('COUNT(%s)', SpyAclGroupsHasRolesTableMap::COL_FK_ACL_ROLE);

        $query->withColumn(SpyAclRoleTableMap::COL_NAME, self::ROLE_NAME);
        $query->withColumn(SpyAclRuleTableMap::COL_TYPE, self::TYPE);
        $query->withColumn(SpyAclRuleTableMap::COL_BUNDLE, self::BUNDLE);
        $query->withColumn(SpyAclRuleTableMap::COL_CONTROLLER, self::CONTROLLER);
        $query->withColumn(SpyAclRuleTableMap::COL_ACTION, self::ACTION);
        $query->withColumn($hasRole, self::HAS_ROLE);

        return $query;
    }

}
