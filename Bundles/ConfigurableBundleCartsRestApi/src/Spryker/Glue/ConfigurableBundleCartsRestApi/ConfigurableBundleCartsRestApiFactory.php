<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapper;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapperInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilder;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\ConfiguredBundleWriter;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\ConfiguredBundleWriterInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig getConfig()
 * @method \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface getClient()
 */
class ConfigurableBundleCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\ConfiguredBundleWriterInterface
     */
    public function createConfiguredBundleWriter(): ConfiguredBundleWriterInterface
    {
        return new ConfiguredBundleWriter(
            $this->createConfiguredBundleRestResponseBuilder(),
            $this->getClient(),
            $this->getCartsRestApiResource(),
            $this->getConfigurableBundleStorageClient(),
            $this->createConfigurableBundleCartMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface
     */
    public function createConfiguredBundleRestResponseBuilder(): ConfiguredBundleRestResponseBuilderInterface
    {
        return new ConfiguredBundleRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createConfigurableBundleCartMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapperInterface
     */
    public function createConfigurableBundleCartMapper(): ConfigurableBundleCartMapperInterface
    {
        return new ConfigurableBundleCartMapper(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface
     */
    public function getCartsRestApiResource(): ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartsRestApiDependencyProvider::RESOURCE_CARTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface
     */
    public function getConfigurableBundleStorageClient(): ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartsRestApiDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_STORAGE);
    }
}
