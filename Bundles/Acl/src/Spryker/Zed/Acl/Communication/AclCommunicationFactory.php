<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
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
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @return UserFacade
     */
    public function createUserFacade()
    {
        trigger_error('Deprecated, use getUserFacade() instead.', E_USER_DEPRECATED);

        return $this->getUserFacade();
    }

    /**
     *
     * @return UserFacade
     */
    public function getUserFacade()
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
     * @param RoleTransfer $roleTransfer
     *
     * @return Form
     */
    public function createRoleForm(RoleTransfer $roleTransfer)
    {
        $form = $this->getFormFactory()
            ->create(new RoleForm(), $roleTransfer);

        return $form;
    }

    /**
     * @param RuleTransfer $ruleTransfer
     *
     * @return Form|FormInterface
     */
    public function createRulesetForm(RuleTransfer $ruleTransfer)
    {
        $form = $this->getFormFactory()->create(new RulesetForm(), $ruleTransfer);

        return $form;
    }

    /**
     * @param int $idAclRole
     *
     * @return RulesetTable
     */
    public function createRulesetTable($idAclRole)
    {
        return new RulesetTable($this->getQueryContainer(), $idAclRole);
    }

}
