<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreator;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpander;
use Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpanderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListRelationshipExpander;
use Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListRelationshipExpanderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractor;
use Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouper;
use Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapper;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapper;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapper;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\UserMapper;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\UserMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReader;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReaderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReader;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReader;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReaderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\WarehouseUserAssignmentReader;
use Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListItemUpdater;
use Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListItemUpdaterInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListUpdater;
use Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListUpdaterInterface;

/**
 * @method \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig getConfig()
 */
class PickingListsBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListUpdaterInterface
     */
    public function createPickingListUpdater(): PickingListUpdaterInterface
    {
        return new PickingListUpdater(
            $this->createPickingListReader(),
            $this->createPickingListMapper(),
            $this->getPickingListFacade(),
            $this->createPickingListResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Updater\PickingListItemUpdaterInterface
     */
    public function createPickingListItemUpdater(): PickingListItemUpdaterInterface
    {
        return new PickingListItemUpdater(
            $this->createPickingListReader(),
            $this->getPickingListFacade(),
            $this->getUtilEncodingService(),
            $this->createPickingListMapper(),
            $this->createPickingListResponseCreator(),
            $this->createPickingListRequestMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListMapperInterface
     */
    public function createPickingListMapper(): PickingListMapperInterface
    {
        return new PickingListMapper(
            $this->createPickingListItemMapper(),
            $this->createUserMapper(),
            $this->createPickingListItemGrouper(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface
     */
    public function createPickingListItemMapper(): PickingListItemMapperInterface
    {
        return new PickingListItemMapper($this->getApiPickingListItemAttributesMapperPlugins());
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Grouper\PickingListItemGrouperInterface
     */
    public function createPickingListItemGrouper(): PickingListItemGrouperInterface
    {
        return new PickingListItemGrouper();
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListRequestMapperInterface
     */
    public function createPickingListRequestMapper(): PickingListRequestMapperInterface
    {
        return new PickingListRequestMapper();
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\UserMapperInterface
     */
    public function createUserMapper(): UserMapperInterface
    {
        return new UserMapper();
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface
     */
    public function createPickingListResponseCreator(): PickingListResponseCreatorInterface
    {
        return new PickingListResponseCreator(
            $this->getConfig(),
            $this->createPickingListMapper(),
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Reader\GlossaryReaderInterface
     */
    public function createGlossaryReader(): GlossaryReaderInterface
    {
        return new GlossaryReader(
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface
     */
    public function createPickingListReader(): PickingListReaderInterface
    {
        return new PickingListReader(
            $this->getPickingListFacade(),
            $this->createPickingListMapper(),
            $this->createPickingListResponseCreator(),
            $this->createPickingListConditionsExpander(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListConditionsExpanderInterface
     */
    public function createPickingListConditionsExpander(): PickingListConditionsExpanderInterface
    {
        return new PickingListConditionsExpander(
            $this->createStockReader(),
            $this->createWarehouseUserAssignmentReader(),
            $this->createWarehouseUserAssignmentExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Expander\PickingListRelationshipExpanderInterface
     */
    public function createPickingListRelationshipExpander(): PickingListRelationshipExpanderInterface
    {
        return new PickingListRelationshipExpander(
            $this->createPickingListReader(),
            $this->createPickingListItemMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Reader\WarehouseUserAssignmentReader
     */
    public function createWarehouseUserAssignmentReader(): WarehouseUserAssignmentReader
    {
        return new WarehouseUserAssignmentReader(
            $this->getWarehouseUserFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractorInterface
     */
    public function createWarehouseUserAssignmentExtractor(): WarehouseUserAssignmentExtractorInterface
    {
        return new WarehouseUserAssignmentExtractor();
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReaderInterface
     */
    public function createStockReader(): StockReaderInterface
    {
        return new StockReader($this->getStockFacade());
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListsBackendApiToPickingListFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::FACADE_PICKING_LIST);
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface
     */
    public function getWarehouseUserFacade(): PickingListsBackendApiToWarehouseUserFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface
     */
    public function getStockFacade(): PickingListsBackendApiToStockFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PickingListsBackendApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): PickingListsBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return list<\Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\ApiPickingListItemsAttributesMapperPluginInterface>
     */
    public function getApiPickingListItemAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(PickingListsBackendApiDependencyProvider::PLUGINS_API_PICKING_LIST_ITEMS_ATTRIBUTES_MAPPER);
    }
}
