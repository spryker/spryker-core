<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\AclPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Acl\AclDependencyProvider;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRoleQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRuleQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclUserHasGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupsHasRolesQuery;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserQuery;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method AclPersistence getFactory()
 */
class AclDependencyContainer extends AbstractPersistenceDependencyContainer
{

    /**
     * @return SpyAclGroupQuery
     */
    public function createGroupQuery()
    {
        return new SpyAclGroupQuery();
    }

    /**
     * @return SpyAclRuleQuery
     */
    public function createRuleQuery()
    {
        return new SpyAclRuleQuery();
    }

    /**
     * @return SpyAclRuleQuery
     */
    public function createRoleQuery()
    {
        return new SpyAclRuleQuery();
    }

    /**
     * @return SpyAclGroupsHasRolesQuery
     */
    public function createGroupHasRoleQuery()
    {
        return new SpyAclGroupsHasRolesQuery();
    }

    /**
     * @return SpyAclUserHasGroupQuery
     */
    public function createUserHasRoleQuery()
    {
        return new SpyAclUserHasGroupQuery();
    }

    /**
     * @return SpyUserQuery
     */
    public function createUserQuery()
    {
        return new SpyUserQuery();
    }

    /**
     * @return SpyAclUserHasGroupQuery
     */
    public function createUserHasGroupQuery()
    {
        return new SpyAclUserHasGroupQuery();
    }

    /**
     * @throws \ErrorException
     *
     * @return UserQueryContainer
     */
    private function createUserQueryContainer()
    {
        return $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_USER);
    }

}
