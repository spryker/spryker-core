<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Navigation\Business\Model\Cache\NavigationCache;
use Spryker\Zed\Navigation\Business\Model\Cache\NavigationCacheBuilder;
use Spryker\Zed\Navigation\Business\Model\Collector\Decorator\NavigationCollectorCacheDecorator;
use Spryker\Zed\Navigation\Business\Model\Collector\NavigationCollector;
use Spryker\Zed\Navigation\Business\Model\Extractor\PathExtractor;
use Spryker\Zed\Navigation\Business\Model\Formatter\MenuFormatter;
use Spryker\Zed\Navigation\Business\Model\NavigationBuilder;
use Spryker\Zed\Navigation\Business\Model\SchemaFinder\NavigationSchemaFinder;
use Spryker\Zed\Navigation\Business\Model\Validator\MenuLevelValidator;
use Spryker\Zed\Navigation\Business\Model\Validator\UrlUniqueValidator;
use Spryker\Zed\Navigation\NavigationDependencyProvider;

/**
 * @method \Spryker\Zed\Navigation\NavigationConfig getConfig()
 */
class NavigationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\NavigationBuilder
     */
    public function createNavigationBuilder()
    {
        return new NavigationBuilder(
            $this->createCachedNavigationCollector(),
            $this->createMenuFormatter(),
            $this->createPathExtractor()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Cache\NavigationCacheBuilder
     */
    public function createNavigationCacheBuilder()
    {
        return new NavigationCacheBuilder(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Formatter\MenuFormatter
     */
    protected function createMenuFormatter()
    {
        $urlBuilder = $this->getUrlBuilder();
        $urlUniqueValidator = $this->createUrlUniqueValidator();
        $menuLevelValidator = $this->createMenuLevelValidator();

        return new MenuFormatter(
            $urlUniqueValidator,
            $menuLevelValidator,
            $urlBuilder
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\SchemaFinder\NavigationSchemaFinder
     */
    protected function createNavigationSchemaFinder()
    {
        return new NavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Collector\NavigationCollector
     */
    protected function createNavigationCollector()
    {
        return new NavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Extractor\PathExtractor
     */
    protected function createPathExtractor()
    {
        return new PathExtractor();
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Validator\MenuLevelValidator
     */
    protected function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return new MenuLevelValidator($maxMenuCount);
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Cache\NavigationCache
     */
    protected function createNavigationCache()
    {
        return new NavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(NavigationDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Collector\Decorator\NavigationCollectorCacheDecorator
     */
    protected function createCachedNavigationCollector()
    {
        return new NavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return \Spryker\Shared\Url\UrlBuilderInterface
     */
    protected function getUrlBuilder()
    {
        return $this->getProvidedDependency(NavigationDependencyProvider::URL_BUILDER);
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Model\Validator\UrlUniqueValidator
     */
    protected function createUrlUniqueValidator()
    {
        return new UrlUniqueValidator();
    }

}
