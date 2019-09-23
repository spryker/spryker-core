<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ProductListUsedByTableDataExpander;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ProductListUsedByTableDataExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Filter\InactiveConfiguredBundleItemFilter;
use Spryker\Zed\ConfigurableBundle\Business\Filter\InactiveConfiguredBundleItemFilterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGenerator;
use Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapper;
use Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReader;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateWriter;
use Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateWriterInterface;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
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
            $this->createConfigurableBundleTemplateNameGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Writer\ConfigurableBundleTemplateTranslationWriterInterface
     */
    public function createConfigurableBundleTemplateTranslationWriter(): ConfigurableBundleTemplateTranslationWriterInterface
    {
        return new ConfigurableBundleTemplateTranslationWriter($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    public function createConfigurableBundleTemplateReader(): ConfigurableBundleTemplateReaderInterface
    {
        return new ConfigurableBundleTemplateReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    public function createConfigurableBundleTemplateSlotReader(): ConfigurableBundleTemplateSlotReaderInterface
    {
        return new ConfigurableBundleTemplateSlotReader(
            $this->getRepository(),
            $this->createConfigurableBundleTranslationExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Generator\ConfigurableBundleTemplateNameGeneratorInterface
     */
    public function createConfigurableBundleTemplateNameGenerator(): ConfigurableBundleTemplateNameGeneratorInterface
    {
        return new ConfigurableBundleTemplateNameGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\ProductListUsedByTableDataExpanderInterface
     */
    public function createProductListUsedByTableDataExpander(): ProductListUsedByTableDataExpanderInterface
    {
        return new ProductListUsedByTableDataExpander(
            $this->createConfigurableBundleTemplateSlotReader(),
            $this->createProductListUsedByTableDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapperInterface
     */
    public function createProductListUsedByTableDataMapper(): ProductListUsedByTableDataMapperInterface
    {
        return new ProductListUsedByTableDataMapper();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    public function createConfigurableBundleTranslationExpander(): ConfigurableBundleTranslationExpanderInterface
    {
        return new ConfigurableBundleTranslationExpander($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ConfigurableBundleToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_GLOSSARY);
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
