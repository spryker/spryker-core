<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;

class SalesOrderItemMetadataMapper
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(SalesProductConnectorToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata[] $salesOrderItemMetadataEntities
     *
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer[]
     */
    public function mapSalesOrderItemMetadataEntityCollectionToItemMetadataTransfers(
        ObjectCollection $salesOrderItemMetadataEntities
    ): array {
        $itemMetadataTransfers = [];

        foreach ($salesOrderItemMetadataEntities as $salesOrderItemMetadataEntity) {
            $itemMetadataTransfers[] = (new ItemMetadataTransfer())
                ->fromArray($salesOrderItemMetadataEntity->toArray(), true)
                ->setSuperAttributes($this->utilEncodingService->decodeJson($salesOrderItemMetadataEntity->getSuperAttributes(), true));
        }

        return $itemMetadataTransfers;
    }
}
