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
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUserQuery;
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
        return $this->getFactory()->createPropelSpyAclGroupQuery();
    }

    /**
     * @return SpyAclRuleQuery
     */
    public function createRuleQuery()
    {
        return $this->getFactory()->createPropelSpyAclRuleQuery();
    }

    /**
     * @return SpyAclRoleQuery
     */
    public function createRoleQuery()
    {
        return $this->getFactory()->createPropelSpyAclRoleQuery();
    }

    /**
     * @return SpyAclGroupsHasRolesQuery
     */
    public function createGroupHasRoleQuery()
    {
        return $this->getFactory()->createPropelSpyAclGroupsHasRolesQuery();
    }

    /**
     * @return SpyAclUserHasGroupQuery
     */
    public function createUserHasRoleQuery()
    {
        return $this->getFactory()->createPropelSpyAclUserHasGroupQuery();
    }

    /**
     * @return SpyUserUserQuery
     */
    public function createUserQuery()
    {
        return $this->createUserQueryContainer()->queryUsers();
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
