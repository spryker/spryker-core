<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\User\Business\UserFacade;
use Orm\Zed\User\Persistence\SpyUserQuery;

class UserDependencyContainer extends AbstractPersistenceDependencyContainer
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
     * @return SpyUserQuery
     */
    public function createUserQuery()
    {
        return new SpyUserQuery();
    }

}
