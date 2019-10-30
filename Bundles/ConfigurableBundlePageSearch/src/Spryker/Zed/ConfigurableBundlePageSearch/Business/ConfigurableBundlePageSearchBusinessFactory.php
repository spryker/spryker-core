<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business;

use Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapper;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher\ConfigurableBundleTemplatePublisher;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher\ConfigurableBundleTemplatePublisherInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeInterface;
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
            $this->createConfigurableBundlePageSearchMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface
     */
    public function createConfigurableBundlePageSearchMapper(): ConfigurableBundleTemplatePageSearchMapperInterface
    {
        return new ConfigurableBundleTemplatePageSearchMapper(
            $this->getUtilEncodingService(),
            $this->getSearchFacade()
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
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeInterface
     */
    public function getSearchFacade(): ConfigurableBundlePageSearchToSearchFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ConfigurableBundlePageSearchToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
