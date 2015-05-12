<?php

namespace SprykerFeature\Zed\Acl\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Acl\Communication\Form\GroupForm;
use SprykerFeature\Zed\Acl\Communication\Form\UserForm;
use SprykerFeature\Zed\Acl\Communication\Grid\RulesetGrid;
use SprykerFeature\Zed\Acl\Communication\Grid\UserGrid;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Request;

class AclDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AclFacade
     */
    public function locateAclFacade()
    {
        return $this->getLocator()->acl()->facade();
    }

    /**
     * @return UserFacade
     */
    public function locateUserFacade()
    {
        return $this->getLocator()->user()->facade();
    }

    /**
     * @return AclQueryContainer
     */
    public function createAclQueryContainer()
    {
        return $this->getLocator()->acl()->queryContainer();
    }

    /**
     * @param Request $request
     *
     * @return UserGrid
     */
    public function createUserGrid(Request $request)
    {
        $aclQueryContainer = $this->createAclQueryContainer();
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
        $aclQueryContainer = $this->createAclQueryContainer();
        $query = $aclQueryContainer->queryGroupUsers($idGroup);

        return $this->getFactory()->createGridUserGrid(
            $query,
            $request,
            $this->getLocator()
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
        $aclQueryContainer = $this->createAclQueryContainer();
        $query = $aclQueryContainer->queryRulesFromGroup($idGroup);

        return $this->getFactory()->createGridRulesetGrid(
            $query,
            $request,
            $this->getLocator()
        );
    }

    /**
     * @param Request $request
     *
     * @return RulesGrid
     */
    public function createGroupsGrid(Request $request)
    {
        $aclQueryContainer = $this->createAclQueryContainer();
        $query = $aclQueryContainer->queryGroup();

        return $this->getFactory()->createGridGroupGrid(
            $query,
            $request,
            $this->getLocator()
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
            $this->createAclQueryContainer()
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
            $this->createAclQueryContainer()
        );
    }
}
