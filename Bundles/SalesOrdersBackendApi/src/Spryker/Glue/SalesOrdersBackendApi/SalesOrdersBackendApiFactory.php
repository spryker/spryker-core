<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\SalesOrdersBackendApi\Dependency\Facade\SalesOrdersBackendApiToSalesFacadeInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapper;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceReader;
use Spryker\Glue\SalesOrdersBackendApi\Processor\Reader\SalesOrdersResourceReaderInterface;

/**
 * @method \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig getConfig()
 */
class SalesOrdersBackendApiFactory extends AbstractBackendApiFactory
{
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
     * @return \Spryker\Glue\SalesOrdersBackendApi\Processor\Mapper\SalesOrdersResourceMapperInterface
     */
    public function createSalesOrdersResourceMapper(): SalesOrdersResourceMapperInterface
    {
        return new SalesOrdersResourceMapper(
            $this->getApiOrdersAttributesMapperPlugins(),
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
     * @return list<\Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface>
     */
    public function getApiOrdersAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrdersBackendApiDependencyProvider::PLUGINS_API_ORDERS_ATTRIBUTES_MAPPER);
    }
}
