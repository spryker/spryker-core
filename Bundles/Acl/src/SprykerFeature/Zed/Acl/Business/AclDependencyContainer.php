<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business;

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
        return $this->getFactory()->createModelGroup(
            $this->getQueryContainer()
        );
    }

    /**
     * @return RoleInterface
     */
    public function createRoleModel()
    {
        return $this->getFactory()->createModelRole(
            $this->createGroupModel(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return Rule
     */
    public function createRuleModel()
    {
        return $this->getFactory()->createModelRule(
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
        return $this->getFactory()->createModelRuleValidator();
    }

    /**
     * @return Installer
     */
    public function createInstallerModel()
    {
        return $this->getFactory()->createModelInstaller(
            $this->createGroupModel(),
            $this->createRoleModel(),
            $this->createRuleModel(),
            $this->getProvidedDependency(AclDependencyProvider::FACADE_USER),
            $this->getConfig()
        );
    }

}
