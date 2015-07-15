<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ApplicationBusiness;
use SprykerFeature\Zed\Application\ApplicationConfig;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\Decorator\NavigationCollectorCacheDecorator;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollector;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor\PathExtractor;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatter;
use SprykerFeature\Zed\Application\Business\Model\Navigation\NavigationBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidator;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidator;
use SprykerFeature\Zed\Application\Business\Model\Url\UrlBuilder;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Psr\Log\LoggerInterface;

/**
 * @method ApplicationBusiness getFactory()
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepCodeCeption();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepDeleteDatabase();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepDeleteGeneratedDirectory();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepInstallDemoData();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepSetupInstall();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepExportKeyValue();
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
        $checkStep = $this->getFactory()->createModelApplicationCheckStepExportSearch();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @return NavigationBuilder
     */
    public function createNavigationBuilder()
    {
        return $this->getFactory()->createModelNavigationNavigationBuilder(
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
        return $this->getFactory()->createModelNavigationCacheNavigationCacheBuilder(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

    /**
     * @return MenuFormatter
     */
    protected function createMenuFormatter()
    {
        $urlBuilder = $this->createUrlBuilder();
        $urlUniqueValidator = $this->createUrlUniqueValidator();
        $menuLevelValidator = $this->createMenuLevelValidator();

        return $this->getFactory()->createModelNavigationFormatterMenuFormatter(
            $urlUniqueValidator,
            $menuLevelValidator,
            $urlBuilder
        );
    }

    /**
     * @return NavigationSchemaFinder
     */
    protected function createNavigationSchemaFinder()
    {
        return $this->getFactory()->createModelNavigationSchemaFinderNavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return NavigationCollector
     */
    protected function createNavigationCollector()
    {
        return $this->getFactory()->createModelNavigationCollectorNavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return PathExtractor
     */
    protected function createPathExtractor()
    {
        return $this->getFactory()->createModelNavigationExtractorPathExtractor();
    }

    /**
     * @return UrlBuilder
     */
    protected function createUrlBuilder()
    {
        return $this->getFactory()->createModelUrlUrlBuilder();
    }

    /**
     * @return UrlUniqueValidator
     */
    protected function createUrlUniqueValidator()
    {
        return $this->getFactory()->createModelNavigationValidatorUrlUniqueValidator();
    }

    /**
     * @return MenuLevelValidator
     */
    protected function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return $this->getFactory()->createModelNavigationValidatorMenuLevelValidator($maxMenuCount);
    }

    /**
     * @return NavigationCache
     */
    private function createNavigationCache()
    {
        return $this->getFactory()->createModelNavigationCacheNavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled()
        );
    }

    /**
     * @return NavigationCollectorCacheDecorator
     */
    private function createCachedNavigationCollector()
    {
        return $this->getFactory()->createModelNavigationCollectorDecoratorNavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

}
