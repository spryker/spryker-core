<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceCreator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceCreatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointAddressCreator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointAddressCreatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointCreator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointCreatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceTypeCreator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceTypeCreatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServicePointByServiceRelationshipExpander;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServicePointByServiceRelationshipExpanderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServiceTypeByServiceRelationshipExpander;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServiceTypeByServiceRelationshipExpanderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServiceByServicePointRelationshipExpander;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServiceByServicePointRelationshipExpanderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServicePointAddressByServicePointRelationshipExpander;
use Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServicePointAddressByServicePointRelationshipExpanderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractor;
use Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilter;
use Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapper;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapper;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapper;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapper;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointAddressReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointAddressReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointAddressUpdater;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointAddressUpdaterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointUpdater;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointUpdaterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceTypeUpdater;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceTypeUpdaterInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceUpdater;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceUpdaterInterface;

/**
 * @method \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig getConfig()
 */
class ServicePointsBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointFacade(),
            $this->createServicePointResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointCreatorInterface
     */
    public function createServicePointCreator(): ServicePointCreatorInterface
    {
        return new ServicePointCreator(
            $this->getServicePointFacade(),
            $this->createServicePointMapper(),
            $this->createServicePointResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointUpdaterInterface
     */
    public function createServicePointUpdater(): ServicePointUpdaterInterface
    {
        return new ServicePointUpdater(
            $this->getServicePointFacade(),
            $this->createServicePointMapper(),
            $this->createServicePointResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointAddressCreatorInterface
     */
    public function createServicePointAddressCreator(): ServicePointAddressCreatorInterface
    {
        return new ServicePointAddressCreator(
            $this->getServicePointFacade(),
            $this->createErrorResponseBuilder(),
            $this->createServicePointAddressMapper(),
            $this->createServicePointAddressResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointAddressUpdaterInterface
     */
    public function createServicePointAddressUpdater(): ServicePointAddressUpdaterInterface
    {
        return new ServicePointAddressUpdater(
            $this->getServicePointFacade(),
            $this->createErrorResponseBuilder(),
            $this->createServicePointAddressMapper(),
            $this->createServicePointAddressResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface
     */
    public function createServicePointResponseBuilder(): ServicePointResponseBuilderInterface
    {
        return new ServicePointResponseBuilder(
            $this->getConfig(),
            $this->createServicePointMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointAddressReaderInterface
     */
    public function createServicePointAddressReader(): ServicePointAddressReaderInterface
    {
        return new ServicePointAddressReader(
            $this->getServicePointFacade(),
            $this->createServicePointAddressResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServicePointAddressByServicePointRelationshipExpanderInterface
     */
    public function createServicePointAddressByServicePointRelationshipExpander(): ServicePointAddressByServicePointRelationshipExpanderInterface
    {
        return new ServicePointAddressByServicePointRelationshipExpander(
            $this->getServicePointFacade(),
            $this->createServicePointAddressMapper(),
            $this->createGlueResourceFilter(),
            $this->createGlueResourceExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Expander\ServicePoint\ServiceByServicePointRelationshipExpanderInterface
     */
    public function createServiceByServicePointRelationshipExpander(): ServiceByServicePointRelationshipExpanderInterface
    {
        return new ServiceByServicePointRelationshipExpander(
            $this->getServicePointFacade(),
            $this->createServiceMapper(),
            $this->createGlueResourceFilter(),
            $this->createGlueResourceExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointAddressResponseBuilderInterface
     */
    public function createServicePointAddressResponseBuilder(): ServicePointAddressResponseBuilderInterface
    {
        return new ServicePointAddressResponseBuilder(
            $this->createServicePointAddressMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ErrorResponseBuilderInterface
     */
    public function createErrorResponseBuilder(): ErrorResponseBuilderInterface
    {
        return new ErrorResponseBuilder(
            $this->getConfig(),
            $this->createServicePointTranslator(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface
     */
    public function createServicePointAddressMapper(): ServicePointAddressMapperInterface
    {
        return new ServicePointAddressMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface
     */
    public function createServicePointMapper(): ServicePointMapperInterface
    {
        return new ServicePointMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface
     */
    public function createServicePointTranslator(): ServicePointTranslatorInterface
    {
        return new ServicePointTranslator(
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeReaderInterface
     */
    public function createServiceTypeReader(): ServiceTypeReaderInterface
    {
        return new ServiceTypeReader(
            $this->getServicePointFacade(),
            $this->createServiceTypeResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceTypeCreatorInterface
     */
    public function createServiceTypeCreator(): ServiceTypeCreatorInterface
    {
        return new ServiceTypeCreator(
            $this->getServicePointFacade(),
            $this->createServiceTypeMapper(),
            $this->createServiceTypeResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceTypeUpdaterInterface
     */
    public function createServiceTypeUpdater(): ServiceTypeUpdaterInterface
    {
        return new ServiceTypeUpdater(
            $this->getServicePointFacade(),
            $this->createServiceTypeMapper(),
            $this->createServiceTypeResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceTypeResponseBuilderInterface
     */
    public function createServiceTypeResponseBuilder(): ServiceTypeResponseBuilderInterface
    {
        return new ServiceTypeResponseBuilder(
            $this->getConfig(),
            $this->createServiceTypeMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface
     */
    public function createServiceMapper(): ServiceMapperInterface
    {
        return new ServiceMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceReaderInterface
     */
    public function createServiceReader(): ServiceReaderInterface
    {
        return new ServiceReader(
            $this->getServicePointFacade(),
            $this->createServiceResponseBuilder(),
            $this->createErrorResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServiceCreatorInterface
     */
    public function createServiceCreator(): ServiceCreatorInterface
    {
        return new ServiceCreator(
            $this->getServicePointFacade(),
            $this->createErrorResponseBuilder(),
            $this->createServiceMapper(),
            $this->createServiceResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServiceUpdaterInterface
     */
    public function createServiceUpdater(): ServiceUpdaterInterface
    {
        return new ServiceUpdater(
            $this->getServicePointFacade(),
            $this->createErrorResponseBuilder(),
            $this->createServiceMapper(),
            $this->createServiceResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServiceResponseBuilderInterface
     */
    public function createServiceResponseBuilder(): ServiceResponseBuilderInterface
    {
        return new ServiceResponseBuilder(
            $this->createServiceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    public function createServiceTypeMapper(): ServiceTypeMapperInterface
    {
        return new ServiceTypeMapper();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServicePointByServiceRelationshipExpanderInterface
     */
    public function createServicePointByServiceRelationshipExpander(): ServicePointByServiceRelationshipExpanderInterface
    {
        return new ServicePointByServiceRelationshipExpander(
            $this->createServicePointByServiceResourceRelationshipReader(),
            $this->createGlueResourceFilter(),
            $this->createGlueResourceExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Expander\Service\ServiceTypeByServiceRelationshipExpanderInterface
     */
    public function createServiceTypeByServiceRelationshipExpander(): ServiceTypeByServiceRelationshipExpanderInterface
    {
        return new ServiceTypeByServiceRelationshipExpander(
            $this->createServiceTypeByServiceResourceRelationshipReader(),
            $this->createGlueResourceFilter(),
            $this->createGlueResourceExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointByServiceResourceRelationshipReaderInterface
     */
    public function createServicePointByServiceResourceRelationshipReader(): ServicePointByServiceResourceRelationshipReaderInterface
    {
        return new ServicePointByServiceResourceRelationshipReader(
            $this->getServicePointFacade(),
            $this->createServicePointMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServiceTypeByServiceResourceRelationshipReaderInterface
     */
    public function createServiceTypeByServiceResourceRelationshipReader(): ServiceTypeByServiceResourceRelationshipReaderInterface
    {
        return new ServiceTypeByServiceResourceRelationshipReader(
            $this->getServicePointFacade(),
            $this->createServiceTypeMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Filter\GlueResourceFilterInterface
     */
    public function createGlueResourceFilter(): GlueResourceFilterInterface
    {
        return new GlueResourceFilter();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\Extractor\GlueResourceExtractorInterface
     */
    public function createGlueResourceExtractor(): GlueResourceExtractorInterface
    {
        return new GlueResourceExtractor();
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointsBackendApiToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointsBackendApiDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ServicePointsBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointsBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
