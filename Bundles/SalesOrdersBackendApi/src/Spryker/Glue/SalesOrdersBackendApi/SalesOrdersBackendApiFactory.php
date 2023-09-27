<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpander;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Filter\PickingListItemResourceFilter;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapper;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceReader;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceReaderInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceRelationshipReader;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceRelationshipReaderInterface;

/**
 * @method \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig getConfig()
 */
class SalesOrdersBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface
     */
    public function createPickingListsSalesOrdersBackendResourceRelationshipExpander(): PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface
    {
        return new PickingListsSalesOrdersBackendResourceRelationshipExpander(
            $this->createSalesOrdersResourceRelationshipReader(),
            $this->createPickingListItemResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceRelationshipReaderInterface
     */
    public function createSalesOrdersResourceRelationshipReader(): SalesOrdersResourceRelationshipReaderInterface
    {
        return new SalesOrdersResourceRelationshipReader($this->createSalesOrdersResourceReader());
    }

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceReaderInterface
     */
    public function createSalesOrdersResourceReader(): SalesOrdersResourceReaderInterface
    {
        return new SalesOrdersResourceReader(
            $this->getSalesFacade(),
            $this->createSalesOrdersResourceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Filter\PickingListItemResourceFilterInterface
     */
    public function createPickingListItemResourceFilter(): PickingListItemResourceFilterInterface
    {
        return new PickingListItemResourceFilter();
    }

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface
     */
    public function createSalesOrdersResourceMapper(): SalesOrdersResourceMapperInterface
    {
        return new SalesOrdersResourceMapper(
            $this->getOrdersBackendApiAttributesMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesOrdersBackendApiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrdersBackendApiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\OrdersBackendApiAttributesMapperPluginInterface>
     */
    public function getOrdersBackendApiAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrdersBackendApiDependencyProvider::PLUGINS_ORDERS_BACKEND_API_ATTRIBUTES_MAPPER);
    }
}
