<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business;

use SprykerFeature\Zed\Acl\Business\Model\Role;
use SprykerFeature\Zed\Acl\Business\Model\Group;
use Generated\Zed\Ide\FactoryAutoCompletion\AclBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\Model\GroupInterface;
use SprykerFeature\Zed\Acl\Business\Model\RoleInterface;
use SprykerFeature\Zed\Acl\AclDependencyProvider;
use SprykerFeature\Zed\Acl\Business\Model\RuleValidator;
use SprykerFeature\Zed\Acl\Business\Model\Installer;
use SprykerFeature\Zed\Acl\Business\Model\Rule;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;

/**
 * @method AclBusiness getFactory()
 * @method AclConfig getConfig()
 * @method AclQueryContainer getQueryContainer()
 */
class AclDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return GroupInterface
     */
    public function createGroupModel()
    {
        return new Group(
            $this->getQueryContainer()
        );
    }

    /**
     * @return RoleInterface
     */
    public function createRoleModel()
    {
        return new Role(
            $this->createGroupModel(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return Rule
     */
    public function createRuleModel()
    {
        return new Rule(
            $this->createGroupModel(),
            $this->getQueryContainer(),
            $this->getProvidedDependency(AclDependencyProvider::FACADE_USER),
            $this->createRuleValidatorHelper(),
            $this->getConfig()
        );
    }

    /**
     * @return RuleValidator
     */
    public function createRuleValidatorHelper()
    {
        return new RuleValidator();
    }

    /**
     * @return Installer
     */
    public function createInstallerModel()
    {
        return new Installer(
            $this->createGroupModel(),
            $this->createRoleModel(),
            $this->createRuleModel(),
            $this->getProvidedDependency(AclDependencyProvider::FACADE_USER),
            $this->getConfig()
        );
    }

}
