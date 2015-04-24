<?php

namespace SprykerFeature\Zed\Acl\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Acl\Communication\Form\UserForm;
use SprykerFeature\Zed\Acl\Communication\Grid\UserGrid;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Request;

class AclDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var AutoCompletion
     */
    protected $locator;
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
     * @return AclSettings
     */
    public function createSettings()
    {
        return $this->locateAclFacade()->getSettings();
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
            $request,
            $this->getLocator()
        );
    }

    /**
     * @param Request $request
     * @param int $idUser
     *
     * @return UserForm
     */
    public function createUserWithGroupForm(Request $request, $idUser = null)
    {
        return $this->getFactory()->createFormUserForm(
            $request,
            $this->getLocator(),
            $this->getFactory(),
            $this->createAclQueryContainer()
        );
    }
}
