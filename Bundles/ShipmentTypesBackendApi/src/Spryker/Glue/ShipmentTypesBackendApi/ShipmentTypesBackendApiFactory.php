<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client\ShipmentTypesBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Creator\ShipmentTypeCreator;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Creator\ShipmentTypeCreatorInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapper;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Reader\ShipmentTypeReader;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Reader\ShipmentTypeReaderInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilder;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslator;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslatorInterface;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Updater\ShipmentTypeUpdater;
use Spryker\Glue\ShipmentTypesBackendApi\Processor\Updater\ShipmentTypeUpdaterInterface;

/**
 * @method \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig getConfig()
 */
class ShipmentTypesBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->createShipmentTypeMapper(),
            $this->createShipmentTypeResponseBuilder(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\Creator\ShipmentTypeCreatorInterface
     */
    public function createShipmentTypeCreator(): ShipmentTypeCreatorInterface
    {
        return new ShipmentTypeCreator(
            $this->createShipmentTypeMapper(),
            $this->createShipmentTypeResponseBuilder(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\Updater\ShipmentTypeUpdaterInterface
     */
    public function createShipmentTypeUpdater(): ShipmentTypeUpdaterInterface
    {
        return new ShipmentTypeUpdater(
            $this->createShipmentTypeMapper(),
            $this->createShipmentTypeResponseBuilder(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\ResponseBuilder\ShipmentTypeResponseBuilderInterface
     */
    public function createShipmentTypeResponseBuilder(): ShipmentTypeResponseBuilderInterface
    {
        return new ShipmentTypeResponseBuilder(
            $this->getConfig(),
            $this->createShipmentTypeMapper(),
            $this->createShipmentTypeTranslator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator\ShipmentTypeTranslatorInterface
     */
    public function createShipmentTypeTranslator(): ShipmentTypeTranslatorInterface
    {
        return new ShipmentTypeTranslator(
            $this->getConfig(),
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    public function createShipmentTypeMapper(): ShipmentTypeMapperInterface
    {
        return new ShipmentTypeMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Facade\ShipmentTypesBackendApiToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypesBackendApiToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypesBackendApiDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client\ShipmentTypesBackendApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ShipmentTypesBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypesBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
