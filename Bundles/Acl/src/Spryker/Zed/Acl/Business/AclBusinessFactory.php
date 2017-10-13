<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business;

use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Business\Model\Group;
use Spryker\Zed\Acl\Business\Model\Installer;
use Spryker\Zed\Acl\Business\Model\Role;
use Spryker\Zed\Acl\Business\Model\Rule;
use Spryker\Zed\Acl\Business\Model\RuleValidator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainer getQueryContainer()
 */
class AclBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Acl\Business\Model\GroupInterface
     */
    public function createGroupModel()
    {
        return new Group(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Model\RoleInterface
     */
    public function createRoleModel()
    {
        return new Role(
            $this->createGroupModel(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Model\Rule
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
     * @return \Spryker\Zed\Acl\Business\Model\RuleValidator
     */
    public function createRuleValidatorHelper()
    {
        return new RuleValidator();
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Model\Installer
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

    /**
     * @return \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(AclDependencyProvider::FACADE_USER);
    }
}
