<?php
namespace SprykerFeature\Zed\Acl\Persistence;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\AclPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclRoleQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclRuleQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\Base\SpyAclUserHasGroupQuery;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclGroupsHasRolesQuery;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUserQuery;

/**
 * @method AclPersistence getFactory()
 */
class AclDependencyContainer extends AbstractDependencyContainer
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
        return $this->getLocator()->user()->queryContainer()->queryUsers();
    }

}
