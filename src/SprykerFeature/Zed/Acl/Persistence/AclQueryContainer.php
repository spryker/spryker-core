<?php
namespace SprykerFeature\Zed\Acl\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclUserHasGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclGroupTableMap;
use SprykerFeature\Zed\Acl\Persistence\Propel\Map\SpyAclUserHasGroupTableMap;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupsHasRolesQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRuleQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRoleQuery;
use SprykerFeature\Zed\User\Persistence\Propel\Map\SpyUserUserTableMap;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUserQuery;

/**
 * Class AclQueryContainer
 *
 * @package SprykerFeature\Zed\Acl\Persistence
 */
/**
 * @method AclDependencyContainer getDependencyContainer()
 */
class AclQueryContainer extends AbstractQueryContainer
{
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
              ->filterByFkUserUser($idUser);

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
     * @param RoleCollection $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return SpyAclRuleQuery
     */
    public function queryRuleByPathAndRoles(
        RoleCollection $roles,
        $bundle = AclSettings::VALIDATOR_WILDCARD,
        $controller = AclSettings::VALIDATOR_WILDCARD,
        $action = AclSettings::VALIDATOR_WILDCARD
    ) {
        $query = $this->getDependencyContainer()->createRuleQuery();

        if ($bundle !== AclSettings::VALIDATOR_WILDCARD) {
            $query->filterByBundle($bundle);
        }

        if ($controller !== AclSettings::VALIDATOR_WILDCARD) {
            $query->filterByController($controller);
        }

        if ($action !== AclSettings::VALIDATOR_WILDCARD) {
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
     * @param int $idUser
     *
     * @return SpyAclGroupQuery
     */
    public function queryUserGroupByIdUser($idUser)
    {
        $query = $this->getDependencyContainer()->createGroupQuery();
        $query->useSpyAclUserHasGroupQuery()
            ->filterByFkUserUser($idUser)
            ->endUse();

        return $query;
    }

    /**
     * @return SpyUserUserQuery
     */
    public function queryUsersWithGroup()
    {
        $query = $this->getDependencyContainer()->createUserQuery();

        $query->addJoin(
            SpyUserUserTableMap::COL_ID_USER_USER,
            SpyAclUserHasGroupTableMap::COL_FK_USER_USER,
            Criteria::LEFT_JOIN
        );

        $query->addJoin(
            SpyAclUserHasGroupTableMap::COL_FK_ACL_GROUP,
            SpyAclGroupTableMap::COL_ID_ACL_GROUP,
            Criteria::LEFT_JOIN
        );

        $query->withColumn(SpyAclGroupTableMap::COL_NAME, 'group_name');
        $query->withColumn(SpyAclGroupTableMap::COL_NAME, 'id_acl_group');

        return $query;
    }
}
