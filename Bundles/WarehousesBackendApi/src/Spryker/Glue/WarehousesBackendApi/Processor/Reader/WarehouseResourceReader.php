<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi\Processor\Reader;

use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;
use Spryker\Glue\WarehousesBackendApi\Dependency\Facade\WarehousesBackendApiToStockFacadeInterface;
use Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapperInterface;

class WarehouseResourceReader implements WarehouseResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\WarehousesBackendApi\Dependency\Facade\WarehousesBackendApiToStockFacadeInterface
     */
    protected WarehousesBackendApiToStockFacadeInterface $stockFacade;

    /**
     * @var \Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapperInterface
     */
    protected WarehouseResourceMapperInterface $warehouseResourceMapper;

    /**
     * @param \Spryker\Glue\WarehousesBackendApi\Dependency\Facade\WarehousesBackendApiToStockFacadeInterface $stockFacade
     * @param \Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapperInterface $warehouseResourceMapper
     */
    public function __construct(
        WarehousesBackendApiToStockFacadeInterface $stockFacade,
        WarehouseResourceMapperInterface $warehouseResourceMapper
    ) {
        $this->stockFacade = $stockFacade;
        $this->warehouseResourceMapper = $warehouseResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer
     */
    public function getWarehouseResourceCollection(StockCriteriaTransfer $stockCriteriaTransfer): WarehouseResourceCollectionTransfer
    {
        $stockCollectionTransfer = $this->stockFacade->getStockCollection($stockCriteriaTransfer);

        return $this->warehouseResourceMapper->mapStockCollectionToWarehouseResourceCollection(
            $stockCollectionTransfer,
            new WarehouseResourceCollectionTransfer(),
        );
    }
}
