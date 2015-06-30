<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Persistence;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\UserPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\User\Business\UserFacade;

/**
 * @method UserPersistence getFactory()
 */
class UserDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AuthFacade
     */
    public function locateFacade()
    {
        return $this->getLocator()->user()->facade();
    }

    /**
     * @return UserFacade
     */
    public function locateInstallerFacade()
    {
        return $this->getLocator()->user()->facade();
    }

    /**
     * @return SpyUserUserQuery
     */
    public function createUserQuery()
    {
        return $this->getFactory()->createPropelSpyUserUserQuery();
    }
}
