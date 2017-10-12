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
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Spryker\Zed\Acl\Communication\Form\RuleForm;
use Spryker\Zed\Acl\Communication\Table\GroupTable;
use Spryker\Zed\Acl\Communication\Table\GroupUsersTable;
use Spryker\Zed\Acl\Communication\Table\RoleTable;
use Spryker\Zed\Acl\Communication\Table\RulesetTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Business\AclFacade getFacade()
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
            $this->getProvidedDependency(AclDependencyProvider::SERVICE_DATE_FORMATTER)
        );
    }

    /**
     * @param int $idAclGroup
     *
     * @return array
     */
    public function getGroupRoleListByGroupId($idAclGroup)
    {
        $roleCollection = $this->getQueryContainer()
            ->queryGroupRoles($idAclGroup)
            ->find()
            ->toArray();

        return [
            'code' => Response::HTTP_OK,
            'idGroup' => $idAclGroup,
            'data' => $roleCollection,
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
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGroupForm(array $data = [], array $options = [])
    {
        $formType = new GroupForm(
            $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_ACL)
        );

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclGroupFormDataProvider
     */
    public function createGroupFormDataProvider()
    {
        return new AclGroupFormDataProvider(
            $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_ACL)
        );
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Table\RoleTable
     */
    public function createRoleTable()
    {
        return new RoleTable(
            $this->getQueryContainer(),
            $this->getProvidedDependency(AclDependencyProvider::SERVICE_DATE_FORMATTER)
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRoleForm(array $data = [], array $options = [])
    {
        $formType = new RoleForm();

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclRoleFormDataProvider
     */
    public function createAclRoleFormDataProvider()
    {
        return new AclRoleFormDataProvider($this->getFacade());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRuleForm(array $data = [], array $options = [])
    {
        $formType = new RuleForm();

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Acl\Communication\Form\DataProvider\AclRuleFormDataProvider
     */
    public function createAclRuleFormDataProvider()
    {
        return new AclRuleFormDataProvider($this->getFacade());
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
}
