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
     * @return \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_USER);
    }
}
