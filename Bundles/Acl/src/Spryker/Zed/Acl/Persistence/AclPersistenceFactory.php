<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Acl\AclDependencyProvider;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\Acl\Persistence\SpyAclRuleQuery;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainer getQueryContainer()
 */
class AclPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function createGroupQuery()
    {
        return new SpyAclGroupQuery();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function createRuleQuery()
    {
        return new SpyAclRuleQuery();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function createRoleQuery()
    {
        return new SpyAclRoleQuery();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function createGroupHasRoleQuery()
    {
        return new SpyAclGroupsHasRolesQuery();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function createUserHasRoleQuery()
    {
        return new SpyAclUserHasGroupQuery();
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function createUserQuery()
    {
        return new SpyUserQuery();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function createUserHasGroupQuery()
    {
        return new SpyAclUserHasGroupQuery();
    }

    /**
     * @throws \ErrorException
     *
     * @return \Spryker\Zed\User\Persistence\UserQueryContainer
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_USER);
    }

}
