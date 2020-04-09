<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilter;
use Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilterInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCache;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheBuilder;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheRemover;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator\ZedNavigationCollectorCacheDecorator;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollector;
use Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractor;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter;
use Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinder;
use Spryker\Zed\ZedNavigation\Business\Model\Validator\MenuLevelValidator;
use Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueValidator;
use Spryker\Zed\ZedNavigation\Business\Model\ZedNavigationBuilder;
use Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider;

/**
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 */
class ZedNavigationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\ZedNavigationBuilder
     */
    public function createNavigationBuilder()
    {
        return new ZedNavigationBuilder(
            $this->createCachedNavigationCollector(),
            $this->createMenuFormatter(),
            $this->createPathExtractor(),
            $this->createNavigationItemFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Filter\NavigationItemFilterInterface
     */
    public function createNavigationItemFilter(): NavigationItemFilterInterface
    {
        return new NavigationItemFilter(
            $this->getNavigationItemFilterPlugins(),
            $this->getNavigationItemCollectionFilterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheBuilder
     */
    public function createNavigationCacheBuilder()
    {
        return new ZedNavigationCacheBuilder(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheRemover
     */
    public function createNavigationCacheRemover(): ZedNavigationCacheRemover
    {
        return new ZedNavigationCacheRemover(
            $this->createNavigationCache()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter
     */
    public function createMenuFormatter()
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
     * @return \Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinder
     */
    public function createNavigationSchemaFinder()
    {
        return new ZedNavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollector
     */
    public function createNavigationCollector()
    {
        return new ZedNavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Extractor\PathExtractor
     */
    public function createPathExtractor()
    {
        return new PathExtractor();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Validator\MenuLevelValidator
     */
    public function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return new MenuLevelValidator($maxMenuCount);
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCache
     */
    public function createNavigationCache()
    {
        return new ZedNavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ZedNavigationDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory::getNavigationItemCollectionFilterPlugins()} instead.
     *
     * @return \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[]
     */
    public function getNavigationItemFilterPlugins(): array
    {
        return $this->getProvidedDependency(ZedNavigationDependencyProvider::PLUGINS_NAVIGATION_ITEM_FILTER);
    }

    /**
     * @return \Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface[]
     */
    public function getNavigationItemCollectionFilterPlugins(): array
    {
        return $this->getProvidedDependency(ZedNavigationDependencyProvider::PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER);
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator\ZedNavigationCollectorCacheDecorator
     */
    public function createCachedNavigationCollector()
    {
        return new ZedNavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Shared\Url\UrlBuilderInterface
     */
    public function getUrlBuilder()
    {
        return $this->getProvidedDependency(ZedNavigationDependencyProvider::URL_BUILDER);
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueValidator
     */
    public function createUrlUniqueValidator()
    {
        return new UrlUniqueValidator();
    }
}
