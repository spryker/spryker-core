<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication;

use Spryker\Zed\Acl\Communication\Table\GroupTable;
use Spryker\Zed\Acl\AclConfig;
use Spryker\Zed\Acl\AclDependencyProvider;
use Spryker\Zed\Acl\Communication\Form\GroupForm;
use Spryker\Zed\Acl\Communication\Form\RoleForm;
use Spryker\Zed\Acl\Communication\Form\RulesetForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Acl\Communication\Table\GroupUsersTable;
use Spryker\Zed\Acl\Communication\Table\RoleTable;
use Spryker\Zed\Acl\Communication\Table\RulesetTable;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Spryker\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method AclQueryContainer getQueryContainer()
 * @method AclConfig getConfig()
 */
class AclCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return UserFacade
     */
    public function createUserFacade()
    {
        return $this->getProvidedDependency(AclDependencyProvider::FACADE_USER);
    }

    /**
     * @return GroupTable
     */
    public function createGroupTable()
    {
        return new GroupTable(
            $this->getQueryContainer()->queryGroup()
        );
    }

    /**
     * @param int $idGroup
     *
     * @return array
     */
    public function createGroupRoleListByGroupId($idGroup)
    {
        $roleCollection = $this->getQueryContainer()
            ->queryGroupRoles($idGroup)
            ->find()
            ->toArray();

        return [
            'code' => Response::HTTP_OK,
            'idGroup' => $idGroup,
            'data' => $roleCollection,
        ];
    }

    /**
     * @param int $idAclGroup
     *
     * @return GroupUsersTable
     */
    public function createGroupUsersTable($idAclGroup)
    {
        return new GroupUsersTable(
            $this->getQueryContainer()->queryGroup(),
            $idAclGroup
        );
    }

    /**
     * @param Request $request
     * @param array $options
     *
     * @return GroupForm
     */
    public function createGroupForm(Request $request, array $options)
    {
        $form = new GroupForm(
            $this->getQueryContainer(),
            $request
        );

        return $this->createForm($form, $options);
    }

    /**
     * @return RoleTable
     */
    public function createRoleTable()
    {
        return new RoleTable($this->getQueryContainer());
    }

    /**
     * @return RoleForm
     */
    public function createRoleForm()
    {
        $form = new RoleForm();

        return $this->createForm($form);
    }

    /**
     * @return RulesetForm
     */
    public function createRulesetForm()
    {
        $form = new RulesetForm();

        return $this->createForm($form);
    }

    /**
     * @param int $idRole
     *
     * @return RulesetTable
     */
    public function createRulesetTable($idRole)
    {
        return new RulesetTable($this->getQueryContainer(), $idRole);
    }

}
