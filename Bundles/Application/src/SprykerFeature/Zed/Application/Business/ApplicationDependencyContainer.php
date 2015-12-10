<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\Decorator\NavigationCollectorCacheDecorator;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidator;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidator;
use SprykerFeature\Zed\Application\Business\Model\Url\UrlBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor\PathExtractor;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollector;
use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatter;
use Psr\Log\LoggerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Application\ApplicationConfig;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\NavigationBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidatorInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidatorInterface;
use SprykerFeature\Zed\Application\Business\Model\Url\UrlBuilderInterface;

/**
 * @method ApplicationConfig getConfig()
 */
class ApplicationDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return AbstractApplicationCheckStep[]
     */
    public function createCheckSteps()
    {
        return $this->getConfig()->getCheckSteps();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return CodeCeption
     */
    public function createCheckStepCodeCeption(LoggerInterface $logger = null)
    {
        $checkStep = new CodeCeption();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return DeleteDatabase
     */
    public function createCheckStepDeleteDatabase(LoggerInterface $logger = null)
    {
        $checkStep = new DeleteDatabase();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return DeleteGeneratedDirectory
     */
    public function createCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
    {
        $checkStep = new DeleteGeneratedDirectory();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return InstallDemoData
     */
    public function createCheckStepInstallDemoData(LoggerInterface $logger = null)
    {
        $checkStep = new InstallDemoData();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return SetupInstall
     */
    public function createCheckStepSetupInstall(LoggerInterface $logger = null)
    {
        $checkStep = new SetupInstall();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ExportKeyValue
     */
    public function createCheckStepExportKeyValue(LoggerInterface $logger = null)
    {
        $checkStep = new ExportKeyValue();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return ExportSearch
     */
    public function createCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $checkStep = new ExportSearch();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @return NavigationBuilder
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
     * @return NavigationCacheBuilder
     */
    public function createNavigationCacheBuilder()
    {
        return new NavigationCacheBuilder(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return MenuFormatterInterface
     */
    protected function createMenuFormatter()
    {
        $urlBuilder = $this->createUrlBuilder();
        $urlUniqueValidator = $this->createUrlUniqueValidator();
        $menuLevelValidator = $this->createMenuLevelValidator();

        return new MenuFormatter(
            $urlUniqueValidator,
            $menuLevelValidator,
            $urlBuilder
        );
    }

    /**
     * @return NavigationSchemaFinderInterface
     */
    protected function createNavigationSchemaFinder()
    {
        return new NavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return NavigationCollectorInterface
     */
    protected function createNavigationCollector()
    {
        return new NavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return PathExtractorInterface
     */
    protected function createPathExtractor()
    {
        return new PathExtractor();
    }

    /**
     * @return UrlBuilderInterface
     */
    protected function createUrlBuilder()
    {
        return new UrlBuilder();
    }

    /**
     * @return UrlUniqueValidatorInterface
     */
    protected function createUrlUniqueValidator()
    {
        return new UrlUniqueValidator();
    }

    /**
     * @return MenuLevelValidatorInterface
     */
    protected function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return new MenuLevelValidator($maxMenuCount);
    }

    /**
     * @return NavigationCacheInterface
     */
    private function createNavigationCache()
    {
        return new NavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled()
        );
    }

    /**
     * @return NavigationCollectorInterface
     */
    private function createCachedNavigationCollector()
    {
        return new NavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

}
