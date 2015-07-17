<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ApplicationBusiness;
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
     * @return MenuFormatterInterface
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
     * @return NavigationSchemaFinderInterface
     */
    protected function createNavigationSchemaFinder()
    {
        return $this->getFactory()->createModelNavigationSchemaFinderNavigationSchemaFinder(
            $this->getConfig()->getNavigationSchemaPathPattern(),
            $this->getConfig()->getNavigationSchemaFileNamePattern()
        );
    }

    /**
     * @return NavigationCollectorInterface
     */
    protected function createNavigationCollector()
    {
        return $this->getFactory()->createModelNavigationCollectorNavigationCollector(
            $this->createNavigationSchemaFinder(),
            $this->getConfig()->getRootNavigationSchema()
        );
    }

    /**
     * @return PathExtractorInterface
     */
    protected function createPathExtractor()
    {
        return $this->getFactory()->createModelNavigationExtractorPathExtractor();
    }

    /**
     * @return UrlBuilderInterface
     */
    protected function createUrlBuilder()
    {
        return $this->getFactory()->createModelUrlUrlBuilder();
    }

    /**
     * @return UrlUniqueValidatorInterface
     */
    protected function createUrlUniqueValidator()
    {
        return $this->getFactory()->createModelNavigationValidatorUrlUniqueValidator();
    }

    /**
     * @return MenuLevelValidatorInterface
     */
    protected function createMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return $this->getFactory()->createModelNavigationValidatorMenuLevelValidator($maxMenuCount);
    }

    /**
     * @return NavigationCacheInterface
     */
    private function createNavigationCache()
    {
        return $this->getFactory()->createModelNavigationCacheNavigationCache(
            $this->getConfig()->getCacheFile(),
            $this->getConfig()->isNavigationCacheEnabled()
        );
    }

    /**
     * @return NavigationCollectorInterface
     */
    private function createCachedNavigationCollector()
    {
        return $this->getFactory()->createModelNavigationCollectorDecoratorNavigationCollectorCacheDecorator(
            $this->createNavigationCollector(),
            $this->createNavigationCache()
        );
    }

}
