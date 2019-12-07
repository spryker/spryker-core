<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Checker\ProductListDeleteChecker;
use Spryker\Zed\ConfigurableBundle\Business\Checker\ProductListDeleteCheckerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateSlotCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateSlotCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfiguredBundleItemCleaner;
use Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfiguredBundleItemCleanerInterface;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateCreator;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateCreatorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateSlotCreator;
use Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateSlotCreatorInterface;
use Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggerer;
use Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ProductListTitleGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ProductListTitleGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateSlotUpdater;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateSlotUpdaterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdater;
use Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdaterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 */
class ConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    public function createConfigurableBundleNameGenerator(): ConfigurableBundleNameGeneratorInterface
    {
        return new ConfigurableBundleNameGenerator(
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ProductListTitleGeneratorInterface
     */
    public function createProductListTitleGenerator(): ProductListTitleGeneratorInterface
    {
        return new ProductListTitleGenerator();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Checker\ProductListDeleteCheckerInterface
     */
    public function createProductListDeleteChecker(): ProductListDeleteCheckerInterface
    {
        return new ProductListDeleteChecker(
            $this->createConfigurableBundleTemplateSlotReader()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateCleanerInterface
     */
    public function createConfigurableBundleTemplateCleaner(): ConfigurableBundleTemplateCleanerInterface
    {
        return new ConfigurableBundleTemplateCleaner(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateReader(),
            $this->getProductImageFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfigurableBundleTemplateSlotCleanerInterface
     */
    public function createConfigurableBundleTemplateSlotCleaner(): ConfigurableBundleTemplateSlotCleanerInterface
    {
        return new ConfigurableBundleTemplateSlotCleaner(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateSlotReader()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Cleaner\ConfiguredBundleItemCleanerInterface
     */
    public function createConfiguredBundleItemCleaner(): ConfiguredBundleItemCleanerInterface
    {
        return new ConfiguredBundleItemCleaner(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface
     */
    public function createConfigurableBundleTemplateSlotProductListExpander(): ConfigurableBundleTemplateSlotProductListExpanderInterface
    {
        return new ConfigurableBundleTemplateSlotProductListExpander(
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    public function createConfigurableBundleTranslationExpander(): ConfigurableBundleTranslationExpanderInterface
    {
        return new ConfigurableBundleTranslationExpander(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateCreatorInterface
     */
    public function createConfigurableBundleTemplateCreator(): ConfigurableBundleTemplateCreatorInterface
    {
        return new ConfigurableBundleTemplateCreator(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createEventTriggerer()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateUpdaterInterface
     */
    public function createConfigurableBundleTemplateUpdater(): ConfigurableBundleTemplateUpdaterInterface
    {
        return new ConfigurableBundleTemplateUpdater(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createConfigurableBundleTemplateReader(),
            $this->createEventTriggerer()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Creator\ConfigurableBundleTemplateSlotCreatorInterface
     */
    public function createConfigurableBundleTemplateSlotCreator(): ConfigurableBundleTemplateSlotCreatorInterface
    {
        return new ConfigurableBundleTemplateSlotCreator(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createProductListWriter(),
            $this->createConfigurableBundleTemplateReader()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Updater\ConfigurableBundleTemplateSlotUpdaterInterface
     */
    public function createConfigurableBundleTemplateSlotUpdater(): ConfigurableBundleTemplateSlotUpdaterInterface
    {
        return new ConfigurableBundleTemplateSlotUpdater(
            $this->getEntityManager(),
            $this->createConfigurableBundleTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createProductListWriter(),
            $this->createConfigurableBundleTemplateSlotReader()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTranslationWriterInterface
     */
    public function createConfigurableBundleTranslationWriter(): ConfigurableBundleTranslationWriterInterface
    {
        return new ConfigurableBundleTranslationWriter(
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getProductListFacade(),
            $this->createProductListTitleGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    public function createConfigurableBundleTemplateReader(): ConfigurableBundleTemplateReaderInterface
    {
        return new ConfigurableBundleTemplateReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander(),
            $this->getLocaleFacade(),
            $this->createConfigurableBundleTemplateImageSetExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateImageSetExpanderInterface
     */
    public function createConfigurableBundleTemplateImageSetExpander(): ConfigurableBundleTemplateImageSetExpanderInterface
    {
        return new ConfigurableBundleTemplateImageSetExpander(
            $this->getRepository(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    public function createConfigurableBundleTemplateSlotReader(): ConfigurableBundleTemplateSlotReaderInterface
    {
        return new ConfigurableBundleTemplateSlotReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander(),
            $this->createConfigurableBundleTemplateSlotProductListExpander(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\EventTriggerer\EventTriggererInterface
     */
    public function createEventTriggerer(): EventTriggererInterface
    {
        return new EventTriggerer($this->getEventFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ConfigurableBundleToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface
     */
    public function getProductListFacade(): ConfigurableBundleToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface
     */
    public function getEventFacade(): ConfigurableBundleToEventFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ConfigurableBundleToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface
     */
    public function getUtilTextService(): ConfigurableBundleToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
