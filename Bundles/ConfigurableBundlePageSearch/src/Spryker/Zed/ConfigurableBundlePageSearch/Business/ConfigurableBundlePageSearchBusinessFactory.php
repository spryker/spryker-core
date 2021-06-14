<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business;

use Laminas\Filter\FilterInterface;
use Laminas\Filter\Word\UnderscoreToDash;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Expander\ConfigurableBundleTemplatePageSearchExpander;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Expander\ConfigurableBundleTemplatePageSearchExpanderInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapper;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher\ConfigurableBundleTemplatePublisher;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher\ConfigurableBundleTemplatePublisherInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Search\DataMapper\ConfigurableBundlePageSearchDataMapper;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Search\DataMapper\ConfigurableBundlePageSearchDataMapperInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Unpublisher\ConfigurableBundleTemplateUnpublisher;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Unpublisher\ConfigurableBundleTemplateUnpublisherInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToProductImageFacadeInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 */
class ConfigurableBundlePageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher\ConfigurableBundleTemplatePublisherInterface
     */
    public function createConfigurableBundleTemplatePublisher(): ConfigurableBundleTemplatePublisherInterface
    {
        return new ConfigurableBundleTemplatePublisher(
            $this->getConfigurableBundleFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createConfigurableBundlePageSearchMapper(),
            $this->createConfigurableBundleTemplatePageSearchExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Unpublisher\ConfigurableBundleTemplateUnpublisherInterface
     */
    public function createConfigurableBundleTemplateUnpublisher(): ConfigurableBundleTemplateUnpublisherInterface
    {
        return new ConfigurableBundleTemplateUnpublisher(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface
     */
    public function createConfigurableBundlePageSearchMapper(): ConfigurableBundleTemplatePageSearchMapperInterface
    {
        return new ConfigurableBundleTemplatePageSearchMapper(
            $this->getUtilEncodingService(),
            $this->createConfigurableBundlePageSearchDataMapper(),
            $this->createConfigurableBundleTemplatePageSearchExpander()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Expander\ConfigurableBundleTemplatePageSearchExpanderInterface
     */
    public function createConfigurableBundleTemplatePageSearchExpander(): ConfigurableBundleTemplatePageSearchExpanderInterface
    {
        return new ConfigurableBundleTemplatePageSearchExpander(
            $this->getProductImageFacade(),
            $this->getConfigurableBundleTemplatePageDataExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface
     */
    public function getConfigurableBundleFacade(): ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::FACADE_CONFIGURABLE_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ConfigurableBundlePageSearchToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ConfigurableBundlePageSearchToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplatePageDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Search\DataMapper\ConfigurableBundlePageSearchDataMapperInterface
     */
    public function createConfigurableBundlePageSearchDataMapper(): ConfigurableBundlePageSearchDataMapperInterface
    {
        return new ConfigurableBundlePageSearchDataMapper(
            $this->getConfigurableBundleTemplateMapExpanderPlugins(),
            $this->createUnderscoreToDashFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplateMapExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplateMapExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_MAP_EXPANDER);
    }

    /**
     * @return \Laminas\Filter\FilterInterface
     */
    public function createUnderscoreToDashFilter(): FilterInterface
    {
        return new UnderscoreToDash();
    }
}
