<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi;

use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreator;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreatorInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapper;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ItemMapper;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ItemMapperInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilder;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\ConfiguredBundleWriter;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\ConfiguredBundleWriterInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\GuestConfiguredBundleWriter;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\GuestConfiguredBundleWriterInterface;
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
            $this->createConfiguredBundleRequestCreator()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer\GuestConfiguredBundleWriterInterface
     */
    public function createGuestConfiguredBundleWriter(): GuestConfiguredBundleWriterInterface
    {
        return new GuestConfiguredBundleWriter(
            $this->createConfiguredBundleRestResponseBuilder(),
            $this->getClient(),
            $this->getCartsRestApiResource(),
            $this->createConfiguredBundleRequestCreator()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreatorInterface
     */
    public function createConfiguredBundleRequestCreator(): ConfiguredBundleRequestCreatorInterface
    {
        return new ConfiguredBundleRequestCreator(
            $this->getConfigurableBundleStorageClient(),
            $this->createConfiguredBundleMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface
     */
    public function createConfiguredBundleRestResponseBuilder(): ConfiguredBundleRestResponseBuilderInterface
    {
        return new ConfiguredBundleRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createConfiguredBundleMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface
     */
    public function createConfiguredBundleMapper(): ConfiguredBundleMapperInterface
    {
        return new ConfiguredBundleMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ItemMapperInterface
     */
    public function createItemMapper(): ItemMapperInterface
    {
        return new ItemMapper($this->getGlossaryStorageClient());
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

    /**
     * @return \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
