<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business;

use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Business\Acl\AclConfigReader;
use Spryker\Zed\Acl\Business\Acl\AclConfigReaderInterface;
use Spryker\Zed\Acl\Business\Filter\NavigationItemFilter;
use Spryker\Zed\Acl\Business\Filter\NavigationItemFilterInterface;
use Spryker\Zed\Acl\Business\Model\Group;
use Spryker\Zed\Acl\Business\Model\Installer;
use Spryker\Zed\Acl\Business\Model\Role;
use Spryker\Zed\Acl\Business\Model\Rule;
use Spryker\Zed\Acl\Business\Model\RuleValidator;
use Spryker\Zed\Acl\Business\Writer\GroupWriter;
use Spryker\Zed\Acl\Business\Writer\GroupWriterInterface;
use Spryker\Zed\Acl\Business\Writer\RoleWriter;
use Spryker\Zed\Acl\Business\Writer\RoleWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\Persistence\AclRepositoryInterface getRepository()
 * @method \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface getEntityManager()
 */
class AclBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Acl\Business\Model\GroupInterface
     */
    public function createGroupModel()
    {
        return new Group($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Model\RoleInterface
     */
    public function createRoleModel()
    {
        return new Role(
            $this->createGroupModel(),
            $this->getQueryContainer(),
            $this->getAclRolesExpanderPlugins(),
            $this->getAclRolePostSavePlugins(),
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
            $this->getConfig(),
            $this->getAclAccessCheckerStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Filter\NavigationItemFilterInterface
     */
    public function createNavigationItemFilter(): NavigationItemFilterInterface
    {
        return new NavigationItemFilter(
            $this->createRuleModel(),
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Acl\AclConfigReaderInterface
     */
    public function createAclConfigReader(): AclConfigReaderInterface
    {
        return new AclConfigReader($this->getConfig());
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
            $this->createAclConfigReader(),
            $this->createRoleWriter(),
            $this->getAclInstallerPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Writer\GroupWriterInterface
     */
    public function createGroupWriter(): GroupWriterInterface
    {
        return new GroupWriter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Business\Writer\RoleWriterInterface
     */
    public function createRoleWriter(): RoleWriterInterface
    {
        return new RoleWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getAclRolePostSavePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(AclDependencyProvider::FACADE_USER);
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface>
     */
    public function getAclInstallerPlugins(): array
    {
        return $this->getProvidedDependency(AclDependencyProvider::ACL_INSTALLER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolesExpanderPluginInterface>
     */
    public function getAclRolesExpanderPlugins(): array
    {
        return $this->getProvidedDependency(AclDependencyProvider::PLUGINS_ACL_ROLES_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface>
     */
    public function getAclRolePostSavePlugins(): array
    {
        return $this->getProvidedDependency(AclDependencyProvider::PLUGINS_ACL_ROLE_POST_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclAccessCheckerStrategyPluginInterface>
     */
    public function getAclAccessCheckerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AclDependencyProvider::PLUGINS_ACL_ACCESS_CHECKER_STRATEGY);
    }
}
