<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\UrlBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Url\Dependency\UrlToLocaleInterface;
use SprykerFeature\Zed\Url\Dependency\UrlToTouchInterface;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @method UrlBusiness getFactory()
 */
class UrlDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return UrlManagerInterface
     */
    public function getUrlManager()
    {
        return $this->getFactory()->createUrlManager(
            $this->getUrlQueryContainer(),
            $this->getLocaleFacade(),
            $this->getTouchFacade(),
            $this->getLocator()
        );
    }

    /**
     * @return UrlQueryContainerInterface
     */
    protected function getUrlQueryContainer()
    {
        return $this->getLocator()->url()->queryContainer();
    }

    /**
     * @return UrlToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return UrlToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return RedirectManagerInterface
     */
    public function getRedirectManager()
    {
        return $this->getFactory()->createRedirectManager(
            $this->getUrlQueryContainer(),
            $this->getUrlManager(),
            $this->getTouchFacade(),
            $this->getLocator()
        );
    }
}
