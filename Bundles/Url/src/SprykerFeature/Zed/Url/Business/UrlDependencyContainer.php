<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\UrlBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;
use SprykerFeature\Zed\Url\UrlDependencyProvider;

/**
 * @method UrlBusiness getFactory()
 * @method UrlQueryContainerInterface getQueryContainer()
 */
class UrlDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return UrlManagerInterface
     */
    public function getUrlManager()
    {
        return new UrlManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(UrlDependencyProvider::FACADE_LOCALE),
            $this->getProvidedDependency(UrlDependencyProvider::FACADE_TOUCH),
            $this->getProvidedDependency(UrlDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return RedirectManagerInterface
     */
    public function getRedirectManager()
    {
        return new RedirectManager(
            $this->getQueryContainer(),
            $this->getUrlManager(),
            $this->getProvidedDependency(UrlDependencyProvider::FACADE_TOUCH),
            $this->getProvidedDependency(UrlDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

}
