<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business;

use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisher;
use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisherInterface;
use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleTemplateImageStoragePublisher;
use Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleTemplateImageStoragePublisherInterface;
use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReader;
use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface;
use Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleStorageUnpublisher;
use Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleStorageUnpublisherInterface;
use Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleTemplateImageStorageUnpublisher;
use Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleTemplateImageStorageUnpublisherInterface;
use Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageDependencyProvider;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToProductImageFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface getEntityManager()
 */
class ConfigurableBundleStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleStoragePublisherInterface
     */
    public function createConfigurableBundleStoragePublisher(): ConfigurableBundleStoragePublisherInterface
    {
        return new ConfigurableBundleStoragePublisher(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createConfigurableBundleReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleStorageUnpublisherInterface
     */
    public function createConfigurableBundleStorageUnpublisher(): ConfigurableBundleStorageUnpublisherInterface
    {
        return new ConfigurableBundleStorageUnpublisher(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Publisher\ConfigurableBundleTemplateImageStoragePublisherInterface
     */
    public function createConfigurableBundleTemplateImageStoragePublisher(): ConfigurableBundleTemplateImageStoragePublisherInterface
    {
        return new ConfigurableBundleTemplateImageStoragePublisher(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createConfigurableBundleReader(),
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getProductImageFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher\ConfigurableBundleTemplateImageStorageUnpublisherInterface
     */
    public function createConfigurableBundleTemplateImageStorageUnpublisher(): ConfigurableBundleTemplateImageStorageUnpublisherInterface
    {
        return new ConfigurableBundleTemplateImageStorageUnpublisher(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface
     */
    public function createConfigurableBundleReader(): ConfigurableBundleReaderInterface
    {
        return new ConfigurableBundleReader(
            $this->getConfigurableBundleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToConfigurableBundleFacadeInterface
     */
    public function getConfigurableBundleFacade(): ConfigurableBundleStorageToConfigurableBundleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::FACADE_CONFIGURABLE_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleStorageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ConfigurableBundleStorageToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }
}
