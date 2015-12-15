<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;
use Spryker\Zed\Url\UrlDependencyProvider;

/**
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
