<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication;

use Spryker\Zed\Acl\Communication\Form\DataProvider\AclGroupFormDataProvider;
use Spryker\Zed\Acl\Communication\Form\DataProvider\AclRoleFormDataProvider;
use Spryker\Zed\Acl\Communication\Form\DataProvider\AclRuleFormDataProvider;
use Spryker\Zed\Acl\Communication\Table\GroupTable;
use Spryker\Zed\Acl\AclConfig;
use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Spryker\Zed\Acl\Communication\Form\RuleForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Acl\Communication\Table\GroupUsersTable;
use Spryker\Zed\Acl\Communication\Table\RoleTable;
use Spryker\Zed\Acl\Communication\Table\RulesetTable;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method AclQueryContainer getQueryContainer()
 * @method AclConfig getConfig()
 */
class AclCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @deprecated Use getUserFacade() instead.
     *
     * @return \Spryker\Zed\User\Business\UserFacade
     */
    public function createUserFacade()
    {
        trigger_error('Deprecated, use getUserFacade() instead.', E_USER_DEPRECATED);

        return $this->getUserFacade();
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacade
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
            $this->getQueryContainer()->queryGroup()
        );
    }

    /**
     * @deprecated Use getGroupRoleListByGroupId() instead.
     *
     * @param $idAclGroup
     *
     * @return array
     */
    public function createGroupRoleListByGroupId($idAclGroup)
    {
        trigger_error('Deprecated, use getGroupRoleListByGroupId() instead.', E_USER_DEPRECATED);

        return $this->getGroupRoleListByGroupId($idAclGroup);
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
        return new GroupUsersTable(
            $this->getQueryContainer()->queryGroup(),
            $idAclGroup
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createGroupForm(array $data = [], array $options = [])
    {
        $formType = new GroupForm(
            $this->getProvidedDependency(AclDependencyProvider::QUERY_CONTAINER_ACL)
        );

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return AclGroupFormDataProvider
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
        return new RoleTable($this->getQueryContainer());
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
     * @return AclRoleFormDataProvider
     */
    public function createAclRoleFormDataProvider()
    {
        return new AclRoleFormDataProvider(
            $this->getProvidedDependency(AclDependencyProvider::FACADE_ACL)
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createRuleForm(array $data = [], array $options = [])
    {
        $formType = new RuleForm();

        return $this->getFormFactory()->create($formType, $data, $options);
    }

    /**
     * @return AclRuleFormDataProvider
     */
    public function createAclRuleFormDataProvider()
    {
        return new AclRuleFormDataProvider(
            $this->getProvidedDependency(AclDependencyProvider::FACADE_ACL)
        );
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
