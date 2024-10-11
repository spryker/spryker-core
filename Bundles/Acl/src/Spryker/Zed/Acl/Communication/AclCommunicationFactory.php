<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication;

use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Communication\Form\DataProvider\AclGroupFormDataProvider;
use Spryker\Zed\Acl\Communication\Form\DataProvider\AclRoleFormDataProvider;
use Spryker\Zed\Acl\Communication\Form\DataProvider\AclRuleFormDataProvider;
use Spryker\Zed\Acl\Communication\Form\DeleteRoleForm;
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Spryker\Zed\Acl\Communication\Form\RuleForm;
use Spryker\Zed\Acl\Communication\Table\GroupTable;
use Spryker\Zed\Acl\Communication\Table\GroupUsersTable;
use Spryker\Zed\Acl\Communication\Table\RoleTable;
use Spryker\Zed\Acl\Communication\Table\RulesetTable;
use Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Persistence\AclRepositoryInterface getRepository()
 * @method \Spryker\Zed\Acl\Persistence\AclEntityManagerInterface getEntityManager()
 */
class AclCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(AclDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Table\GroupTable
     */
    public function createGroupTable()
    {
        return new GroupTable(
            $this->getQueryContainer()->queryGroup(),
            $this->getProvidedDependency(AclDependencyProvider::SERVICE_DATE_FORMATTER),
        );
    }

    /**
     * @param int $idAclGroup
     *
     * @return array
     */
    public function getGroupRoleListByGroupId($idAclGroup)
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $roleCollection */
        $roleCollection = $this->getQueryContainer()
            ->queryGroupRoles($idAclGroup)
            ->find();

        return [
            'code' => Response::HTTP_OK,
            'idGroup' => $idAclGroup,
            'data' => $roleCollection->toArray(),
        ];
    }

    /**
     * @param int $idAclGroup
     *
     * @return \Spryker\Zed\Acl\Communication\Table\GroupUsersTable
     */
    public function createGroupUsersTable($idAclGroup)
    {
        return new GroupUsersTable($this->getQueryContainer(), $idAclGroup);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGroupForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(GroupForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclGroupFormDataProvider
     */
    public function createGroupFormDataProvider()
    {
        return new AclGroupFormDataProvider(
            $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_ACL),
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Table\RoleTable
     */
    public function createRoleTable()
    {
        return new RoleTable(
            $this->getQueryContainer(),
            $this->getProvidedDependency(AclDependencyProvider::SERVICE_DATE_FORMATTER),
        );
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRoleForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(RoleForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclRoleFormDataProvider
     */
    public function createAclRoleFormDataProvider()
    {
        /** @var \Spryker\Zed\Acl\Business\AclFacade $facade */
        $facade = $this->getFacade();

        return new AclRoleFormDataProvider($facade);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRuleForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(RuleForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclRuleFormDataProvider
     */
    public function createAclRuleFormDataProvider()
    {
        /** @var \Spryker\Zed\Acl\Business\AclFacade $facade */
        $facade = $this->getFacade();

        return new AclRuleFormDataProvider($facade, $this->getRouterFacade());
    }

    /**
     * @param int $idAclRole
     *
     * @return \Spryker\Zed\Acl\Communication\Table\RulesetTable
     */
    public function createRulesetTable($idAclRole)
    {
        return new RulesetTable($this->getQueryContainer(), $idAclRole);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteRoleForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteRoleForm::class, [], [
            'fields' => [],
        ]);
    }

    /**
     * @return \Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface
     */
    public function getRouterFacade(): AclToRouterFacadeInterface
    {
        return $this->getProvidedDependency(AclDependencyProvider::FACADE_ROUTER);
    }
}
