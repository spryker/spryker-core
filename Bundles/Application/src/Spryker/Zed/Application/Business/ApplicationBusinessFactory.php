<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Application\ApplicationDependencyProvider;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\Decorator\NavigationCollectorCacheDecorator;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollector;
use Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractor;
use Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatter;
use Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinder;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidator;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep[]
     */
    public function createCheckSteps(LoggerInterface $logger = null)
    {
        return [
            $this->createCheckStepDeleteDatabase($logger),
            $this->createCheckStepDeleteGeneratedDirectory($logger),
            $this->createCheckStepSetupInstall($logger),
            $this->createCheckStepCodeCeption($logger),
            $this->createCheckStepInstallDemoData($logger),
            $this->createCheckStepExportKeyValue($logger),
            $this->createCheckStepExportSearch($logger),
        ];
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption
     */
    public function createCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $checkStep = new CodeCeption();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase
     */
    public function createCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $checkStep = new DeleteDatabase();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory
     */
    public function createCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $checkStep = new DeleteGeneratedDirectory();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData
     */
    public function createCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $checkStep = new InstallDemoData();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall
     */
    public function createCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $checkStep = new SetupInstall();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue
     */
    public function createCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $checkStep = new ExportKeyValue();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return \Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch
     */
    public function createCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $checkStep = new ExportSearch();
        if ($logger !== null) {
            $checkStep->setLogger($logger);
        }

        return $checkStep;
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder
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
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder
     */
    public function createNavigationCacheBuilder()
    {
        return new NavigationCacheBuilder(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface
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
     * @return \Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface
     */
    protected function createNavigationSchemaFinder()
    {
        return new NavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface
     */
    protected function createNavigationCollector()
    {
        return new NavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface
     */
    protected function createPathExtractor()
    {
        return new PathExtractor();
    }

    /**
     * @deprecated 1.0.0 Use getUrlBuilder() instead.
     *
     * @return \Spryker\Shared\Url\UrlBuilderInterface
     */
    protected function createUrlBuilder()
    {
        trigger_error('Deprecated, use getUrlBuilder() instead.', E_USER_DEPRECATED);

        return $this->getUrlBuilder();
    }

    /**
     * @return \Spryker\Shared\Url\UrlBuilderInterface
     */
    protected function getUrlBuilder()
    {
        return $this->getProvidedDependency(ApplicationDependencyProvider::URL_BUILDER);
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidatorInterface
     */
    protected function createUrlUniqueValidator()
    {
        return new UrlUniqueValidator();
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidatorInterface
     */
    protected function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return new MenuLevelValidator($maxMenuCount);
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface
     */
    protected function createNavigationCache()
    {
        return new NavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled()
        );
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface
     */
    protected function createCachedNavigationCollector()
    {
        return new NavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

}
