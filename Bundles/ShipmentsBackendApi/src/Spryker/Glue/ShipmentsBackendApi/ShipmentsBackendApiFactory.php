<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ShipmentsBackendApi\Dependency\Facade\ShipmentsBackendApiToShipmentFacadeInterface;
use Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapper;
use Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapperInterface;
use Spryker\Glue\ShipmentsBackendApi\Processor\Reader\SalesShipmentReader;
use Spryker\Glue\ShipmentsBackendApi\Processor\Reader\SalesShipmentReaderInterface;

class ShipmentsBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentsBackendApi\Processor\Reader\SalesShipmentReaderInterface
     */
    public function createSalesShipmentReader(): SalesShipmentReaderInterface
    {
        return new SalesShipmentReader(
            $this->createSalesShipmentMapper(),
            $this->getShipmentFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapperInterface
     */
    public function createSalesShipmentMapper(): SalesShipmentMapperInterface
    {
        return new SalesShipmentMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsBackendApi\Dependency\Facade\ShipmentsBackendApiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentsBackendApiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentsBackendApiDependencyProvider::FACADE_SHIPMENT);
    }
}
