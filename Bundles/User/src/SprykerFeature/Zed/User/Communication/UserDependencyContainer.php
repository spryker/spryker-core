<?php

namespace SprykerFeature\Zed\User\Communication;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\UserCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\User\Business\UserFacade;

/**
 * @method UserCommunication getFactory()
 */
class UserDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AuthFacade
     */
    public function getFacade()
    {
        return $this->getLocator()->user()->facade();
    }

    /**
     * @return UserFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->user()->facade();
    }
}
