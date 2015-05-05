<?php

namespace SprykerFeature\Zed\Acl\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\AclBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\Model\RuleValidator;
use SprykerFeature\Zed\Acl\Business\Model\Group;
use SprykerFeature\Zed\Acl\Business\Model\Installer;
use SprykerFeature\Zed\Acl\Business\Model\Role;
use SprykerFeature\Zed\Acl\Business\Model\Rule;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;

/**
 * @method AclBusiness getFactory()
 * @method AclConfig getConfig()
 */
class AclDependencyContainer extends AbstractDependencyContainer
{
    /**
    * @var AclQueryContainer
    */
    protected $queryContainer;

    /**
     * @return Group
     */
    public function createGroupModel()
    {
        return $this->getFactory()->createModelGroup(
            $this->locateQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return Role
     */
    public function createRoleModel()
    {
        return $this->getFactory()->createModelRole(
            $this->locateQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return Rule
     */
    public function createRuleModel()
    {
        return $this->getFactory()->createModelRule(
            $this->locateQueryContainer(),
            $this->getLocator(),
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
     * @return AclQueryContainer
     */
    protected function locateQueryContainer()
    {
        if (empty($this->queryContainer)) {
            $this->queryContainer = $this->getLocator()->acl()->queryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @return Installer
     */
    public function createInstallerModel()
    {
        return $this->getFactory()->createModelInstaller(
            $this->getLocator()->acl()->facade(),
            $this->getLocator()->user()->facade(),
            $this->getConfig()
        );
    }
}
