<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Persistence;

use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\Acl\Persistence\SpyAclRuleQuery;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Persistence\Propel\Mapper\AclMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\Persistence\AclRepositoryInterface getRepository()
 * @method \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface getEntityManager()
 */
class AclPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function createGroupQuery()
    {
        return SpyAclGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function createRuleQuery()
    {
        return SpyAclRuleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function createRoleQuery()
    {
        return SpyAclRoleQuery::create();
    }

    /**
     * @return \Spryker\Zed\Acl\Persistence\Propel\Mapper\AclMapper
     */
    public function createAclMapper(): AclMapper
    {
        return new AclMapper();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupsHasRolesQuery
     */
    public function createGroupHasRoleQuery()
    {
        return SpyAclGroupsHasRolesQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function createUserHasRoleQuery()
    {
        return SpyAclUserHasGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function createUserQuery()
    {
        return SpyUserQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function createUserHasGroupQuery()
    {
        return SpyAclUserHasGroupQuery::create();
    }

    /**
     * @return \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_USER);
    }
}
