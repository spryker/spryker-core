<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Filter\InactiveConfiguredBundleItemFilter;
use Spryker\Zed\ConfigurableBundle\Business\Filter\InactiveConfiguredBundleItemFilterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotTranslationWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
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
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateWriterInterface
     */
    public function createConfigurableBundleTemplateWriter(): ConfigurableBundleTemplateWriterInterface
    {
        return new ConfigurableBundleTemplateWriter(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateTranslationWriter(),
            $this->createConfigurableBundleNameGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotWriterInterface
     */
    public function createConfigurableBundleTemplateSlotWriter(): ConfigurableBundleTemplateSlotWriterInterface
    {
        return new ConfigurableBundleTemplateSlotWriter(
            $this->getEntityManager(),
            $this->createConfigurableBundleTemplateSlotTranslationWriter(),
            $this->createConfigurableBundleNameGenerator(),
            $this->createProductListWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface
     */
    public function createConfigurableBundleTemplateTranslationWriter(): ConfigurableBundleTemplateTranslationWriterInterface
    {
        return new ConfigurableBundleTemplateTranslationWriter(
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateSlotTranslationWriterInterface
     */
    public function createConfigurableBundleTemplateSlotTranslationWriter(): ConfigurableBundleTemplateSlotTranslationWriterInterface
    {
        return new ConfigurableBundleTemplateSlotTranslationWriter($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->createConfigurableBundleTemplateReader(),
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    public function createConfigurableBundleTemplateReader(): ConfigurableBundleTemplateReaderInterface
    {
        return new ConfigurableBundleTemplateReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleNameGeneratorInterface
     */
    public function createConfigurableBundleNameGenerator(): ConfigurableBundleNameGeneratorInterface
    {
        return new ConfigurableBundleNameGenerator($this->getUtilTextService());
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
     * @return \Spryker\Zed\ConfigurableBundle\Business\Filter\InactiveConfiguredBundleItemFilterInterface
     */
    public function createInactiveConfiguredBundleItemFilter(): InactiveConfiguredBundleItemFilterInterface
    {
        return new InactiveConfiguredBundleItemFilter(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Service\ConfigurableBundleToUtilTextServiceInterface
     */
    public function getUtilTextService(): ConfigurableBundleToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
