<?php

namespace SprykerFeature\Zed\Application\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ApplicationBusiness;
use SprykerFeature\Shared\Library\Bundle\BundleConfig;
use SprykerFeature\Zed\Application\ApplicationConfig;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\CodeCeption;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteDatabase;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\DeleteGeneratedDirectory;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportKeyValue;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\ExportSearch;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\InstallDemoData;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\SetupInstall;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollector;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor\PathExtractor;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter\MenuFormatter;
use SprykerFeature\Zed\Application\Business\Model\Navigation\NavigationBuilder;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\MenuLevelValidator;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Validator\UrlUniqueValidator;
use SprykerFeature\Zed\Application\Business\Model\Url\UrlBuilder;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use Psr\Log\LoggerInterface;

/**
 * @method ApplicationBusiness getFactory()
 * @method ApplicationConfig getConfig()
 */
class ApplicationDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AbstractApplicationCheckStep[]
     */
    public function getCheckSteps()
    {
        return $this->getConfig()->getCheckSteps();
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return CodeCeption
     */
    public function getCheckStepCodeCeption(LoggerInterface $logger = null)
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
    public function getCheckStepDeleteDatabase(LoggerInterface $logger = null)
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
    public function getCheckStepDeleteGeneratedDirectory(LoggerInterface $logger = null)
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
    public function getCheckStepInstallDemoData(LoggerInterface $logger = null)
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
    public function getCheckStepSetupInstall(LoggerInterface $logger = null)
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
    public function getCheckStepExportKeyValue(LoggerInterface $logger = null)
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
    public function getCheckStepExportSearch(LoggerInterface $logger = null)
    {
        $checkStep = $this->getFactory()->createModelApplicationCheckStepExportSearch();
        $checkStep->setLogger($logger);

        return $checkStep;
    }

    /**
     * @return NavigationBuilder
     */
    public function getNavigationBuilder()
    {
        $navigationCollector = $this->getNavigationCollector();
        $menuFormatter = $this->getMenuFormatter();
        $pathExtractor = $this->getPathExtractor();

        return $this->getFactory()->createModelNavigationNavigationBuilder(
            new BundleConfig(),
            $navigationCollector,
            $menuFormatter,
            $pathExtractor
        );
    }

    /**
     * @return MenuFormatter
     */
    protected function getMenuFormatter()
    {
        $urlBuilder = $this->getUrlBuilder();
        $urlUniqueValidator = $this->getUrlUniqueValidator();
        $menuLevelValidator = $this->getMenuLevelValidator();

        return $this->getFactory()->createModelNavigationFormatterMenuFormatter(
            $urlUniqueValidator,
            $menuLevelValidator,
            $urlBuilder
        );
    }

    /**
     * @return NavigationCollector
     */
    protected function getNavigationCollector()
    {
        return $this->getFactory()->createModelNavigationCollectorNavigationCollector();
    }

    /**
     * @return PathExtractor
     */
    protected function getPathExtractor()
    {
        return $this->getFactory()->createModelNavigationExtractorPathExtractor();
    }

    /**
     * @return UrlBuilder
     */
    protected function getUrlBuilder()
    {
        return $this->getFactory()->createModelUrlUrlBuilder();
    }

    /**
     * @return UrlUniqueValidator
     */
    protected function getUrlUniqueValidator()
    {
        return $this->getFactory()->createModelNavigationValidatorUrlUniqueValidator();
    }

    /**
     * @return MenuLevelValidator
     */
    protected function getMenuLevelValidator()
    {
        $maxMenuCount = $this->getConfig()->getMaxMenuLevelCount();

        return $this->getFactory()->createModelNavigationValidatorMenuLevelValidator($maxMenuCount);
    }
}
