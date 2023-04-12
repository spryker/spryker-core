<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointCreator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Creator\ServicePointCreatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapper;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointReader;
use Spryker\Glue\ServicePointsBackendApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilder;
use Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslator;
use Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointUpdater;
use Spryker\Glue\ServicePointsBackendApi\Processor\Updater\ServicePointUpdaterInterface;

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
        );
    }

    /**
     * @return \Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder\ServicePointResponseBuilderInterface
     */
    public function createServicePointResponseBuilder(): ServicePointResponseBuilderInterface
    {
        return new ServicePointResponseBuilder(
            $this->getConfig(),
            $this->createServicePointTranslator(),
            $this->createServicePointMapper(),
        );
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
