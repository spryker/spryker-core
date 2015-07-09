<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\AclCommunication;
use SprykerFeature\Zed\Acl\AclDependencyProvider;
use SprykerFeature\Zed\Acl\Communication\Form\GroupForm;
use SprykerFeature\Zed\Acl\Communication\Form\UserForm;
use SprykerFeature\Zed\Acl\Communication\Grid\RulesetGrid;
use SprykerFeature\Zed\Acl\Communication\Grid\UserGrid;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AclCommunication getFactory()
 * @method AclQueryContainer getQueryContainer()
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
     * @param Request $request
     *
     * @return UserGrid
     */
    public function createUserGrid(Request $request)
    {
        $aclQueryContainer = $this->getQueryContainer();
        $query = $aclQueryContainer->queryUsersWithGroup();

        return $this->getFactory()->createGridUserGrid(
            $query,
            $request
        );
    }

    /**
     * @param Request $request
     * @param int $idGroup
     *
     * @return UserGrid
     */
    public function createUserGridByGroupId(Request $request, $idGroup)
    {
        $aclQueryContainer = $this->getQueryContainer();
        $query = $aclQueryContainer->queryGroupUsers($idGroup);

        return $this->getFactory()->createGridUserGrid(
            $query,
            $request
        );
    }

    /**
     * @param Request $request
     * @param int $idGroup
     *
     * @return RulesetGrid
     */
    public function createRulesetGrid(Request $request, $idGroup)
    {
        $aclQueryContainer = $this->getQueryContainer();
        $query = $aclQueryContainer->queryRulesFromGroup($idGroup);

        return $this->getFactory()->createGridRulesetGrid(
            $query,
            $request
        );
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
     * @return UserForm
     */
    public function createUserWithGroupForm(Request $request)
    {
        return $this->getFactory()->createFormUserForm(
            $request,
            $this->getFactory(),
            $this->getQueryContainer()
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
            $request,
            $this->getFactory(),
            $this->getQueryContainer()
        );
    }

}
