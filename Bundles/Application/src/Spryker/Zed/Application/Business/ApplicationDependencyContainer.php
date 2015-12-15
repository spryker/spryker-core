<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business;

use Spryker\Zed\Application\Business\Model\Navigation\Collector\Decorator\NavigationCollectorCacheDecorator;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidator;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidator;
use Spryker\Zed\Application\Business\Model\Url\UrlBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractor;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollector;
use Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinder;
use Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatter;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Application\ApplicationConfig;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData;
use Spryker\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Extractor\PathExtractorInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatterInterface;
use Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidatorInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidatorInterface;
use Spryker\Zed\Application\Business\Model\Url\UrlBuilderInterface;

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
        return [
            $this->createCheckStepDeleteDatabase(),
            $this->createCheckStepDeleteGeneratedDirectory(),
            $this->createCheckStepSetupInstall(),
            $this->createCheckStepCodeCeption(),
            $this->createCheckStepInstallDemoData(),
            $this->createCheckStepExportKeyValue(),
            $this->createCheckStepExportSearch(),
        ];
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
