<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;
use Spryker\Glue\ShipmentsBackendApi\Dependency\Facade\ShipmentsBackendApiToShipmentFacadeInterface;
use Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapperInterface;

class SalesShipmentReader implements SalesShipmentReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapperInterface
     */
    protected SalesShipmentMapperInterface $salesShipmentMapper;

    /**
     * @var \Spryker\Glue\ShipmentsBackendApi\Dependency\Facade\ShipmentsBackendApiToShipmentFacadeInterface
     */
    protected ShipmentsBackendApiToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Glue\ShipmentsBackendApi\Processor\Mapper\SalesShipmentMapperInterface $salesShipmentMapper
     * @param \Spryker\Glue\ShipmentsBackendApi\Dependency\Facade\ShipmentsBackendApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(SalesShipmentMapperInterface $salesShipmentMapper, ShipmentsBackendApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->salesShipmentMapper = $salesShipmentMapper;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function getSalesShipmentResourceCollection(SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer): SalesShipmentResourceCollectionTransfer
    {
        $salesShipmentCollectionTransfer = $this->shipmentFacade->getSalesShipmentCollection($salesShipmentCriteriaTransfer);

        return $this->salesShipmentMapper->mapSalesShipmentCollectionToSalesShipmentResourceCollection(
            $salesShipmentCollectionTransfer,
            new SalesShipmentResourceCollectionTransfer(),
        );
    }
}
