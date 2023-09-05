<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi;

use Spryker\Glue\Kernel\AbstractStorefrontApiFactory;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ErrorResponseBuilder;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilder;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilder;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilder;
use Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Expander\ServicePointAddressRelationshipExpander;
use Spryker\Glue\ServicePointsRestApi\Processor\Expander\ServicePointAddressRelationshipExpanderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointAddressMapper;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapper;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapper;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapperInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReader;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReaderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointReader;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReader;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServiceTypeResourceReader;
use Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServiceTypeResourceReaderInterface;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig getConfig()
 */
class ServicePointsRestApiFactory extends AbstractStorefrontApiFactory
{
    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointSearchClient(),
            $this->createServicePointStorageReader(),
            $this->createServicePointRequestBuilder(),
            $this->createServicePointResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointAddressReaderInterface
     */
    public function createServicePointAddressReader(): ServicePointAddressReaderInterface
    {
        return new ServicePointAddressReader(
            $this->createServicePointStorageReader(),
            $this->createServicePointAddressResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServicePointStorageReaderInterface
     */
    public function createServicePointStorageReader(): ServicePointStorageReaderInterface
    {
        return new ServicePointStorageReader(
            $this->getServicePointStorageClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Reader\ServiceTypeResourceReaderInterface
     */
    public function createServiceTypeResourceReader(): ServiceTypeResourceReaderInterface
    {
        return new ServiceTypeResourceReader(
            $this->createServiceTypeMapper(),
            $this->getServicePointStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Expander\ServicePointAddressRelationshipExpanderInterface
     */
    public function createServicePointAddressRelationshipExpander(): ServicePointAddressRelationshipExpanderInterface
    {
        return new ServicePointAddressRelationshipExpander(
            $this->createServicePointAddressReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointSearchRequestBuilderInterface
     */
    public function createServicePointRequestBuilder(): ServicePointSearchRequestBuilderInterface
    {
        return new ServicePointSearchRequestBuilder(
            $this->getConfig(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointResponseBuilderInterface
     */
    public function createServicePointResponseBuilder(): ServicePointResponseBuilderInterface
    {
        return new ServicePointResponseBuilder(
            $this->getResourceBuilder(),
            $this->createErrorResponseBuilder(),
            $this->createServicePointMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ServicePointAddressResponseBuilderInterface
     */
    public function createServicePointAddressResponseBuilder(): ServicePointAddressResponseBuilderInterface
    {
        return new ServicePointAddressResponseBuilder(
            $this->getResourceBuilder(),
            $this->createErrorResponseBuilder(),
            $this->createServicePointAddressMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Builder\ErrorResponseBuilderInterface
     */
    public function createErrorResponseBuilder(): ErrorResponseBuilderInterface
    {
        return new ErrorResponseBuilder(
            $this->getConfig(),
            $this->getResourceBuilder(),
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointMapperInterface
     */
    public function createServicePointMapper(): ServicePointMapperInterface
    {
        return new ServicePointMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServicePointAddressMapperInterface
     */
    public function createServicePointAddressMapper(): ServicePointAddressMapperInterface
    {
        return new ServicePointAddressMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    public function createServiceTypeMapper(): ServiceTypeMapperInterface
    {
        return new ServiceTypeMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface
     */
    public function getServicePointStorageClient(): ServicePointsRestApiToServicePointStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientInterface
     */
    public function getServicePointSearchClient(): ServicePointsRestApiToServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface
     */
    public function getStoreClient(): ServicePointsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ServicePointsRestApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ServicePointsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
