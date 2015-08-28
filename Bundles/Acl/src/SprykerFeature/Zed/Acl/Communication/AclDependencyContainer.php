<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\AclCommunication;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\AclDependencyProvider;
use SprykerFeature\Zed\Acl\Communication\Form\GroupForm;
use SprykerFeature\Zed\Acl\Communication\Form\RoleForm;
use SprykerFeature\Zed\Acl\Communication\Form\RulesetForm;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Acl\Communication\Table\RoleTable;
use SprykerFeature\Zed\Acl\Communication\Table\RulesetTable;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method AclCommunication getFactory()
 * @method AclQueryContainer getQueryContainer()
 * @method AclConfig getConfig()
 */
class AclDependencyContainer extends AbstractCommunicationDependencyContainer
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
        return $this->getFactory()->createTableGroupTable(
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
            ->toArray()
        ;

        return [
            'code' => Response::HTTP_OK,
            'idGroup' => $idGroup,
            'data' => $roleCollection,
        ];
    }

    /**
     * @param Request $request
     *
     * @return RulesGrid
     */
    public function createGroupsGrid(Request $request)
    {
        $aclQueryContainer = $this->getQueryContainer();
        $query = $aclQueryContainer->queryGroup();

        return $this->getFactory()->createGridGroupGrid(
            $query,
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return GroupForm
     */
    public function createGroupForm(Request $request)
    {
        return $this->getFactory()->createFormGroupForm(
            $this->getQueryContainer(),
            $request
        );
    }

    /**
     * @return RoleTable
     */
    public function createRoleTable()
    {
        return $this->getFactory()->createTableRoleTable($this->getQueryContainer());
    }

    /**
     * @return RoleForm
     */
    public function createRoleForm()
    {
        return $this->getFactory()->createFormRoleForm();
    }

    /**
     * @return RulesetForm
     */
    public function createRulesetForm()
    {
        return $this->getFactory()->createFormRulesetForm();
    }

    /**
     * @param int $idRole
     *
     * @return RulesetTable
     */
    public function createRulesetTable($idRole)
    {
        return $this->getFactory()->createTableRulesetTable($this->getQueryContainer(), $idRole);
    }

}
