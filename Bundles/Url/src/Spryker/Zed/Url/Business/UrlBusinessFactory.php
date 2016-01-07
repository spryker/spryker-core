<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;
use Spryker\Zed\Url\UrlDependencyProvider;
use Spryker\Zed\Url\UrlConfig;

/**
 * @method UrlQueryContainerInterface getQueryContainer()
 * @method UrlConfig getConfig()
 */
class UrlBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return UrlManagerInterface
     */
    public function createUrlManager()
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
    public function createRedirectManager()
    {
        return new RedirectManager(
            $this->getQueryContainer(),
            $this->createUrlManager(),
            $this->getProvidedDependency(UrlDependencyProvider::FACADE_TOUCH),
            $this->getProvidedDependency(UrlDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

}
