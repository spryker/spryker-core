<?php

namespace SprykerFeature\Zed\Acl\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\User\Business\UserFacade;

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
}
