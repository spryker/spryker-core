<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Propel\Runtime\Collection\Collection;
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
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata> $salesOrderItemMetadataEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemMetadataTransfer>
     */
    public function mapSalesOrderItemMetadataEntityCollectionToItemMetadataTransfers(
        Collection $salesOrderItemMetadataEntities
    ): array {
        $itemMetadataTransfers = [];

        foreach ($salesOrderItemMetadataEntities as $salesOrderItemMetadataEntity) {
            $itemMetadataTransfers[] = (new ItemMetadataTransfer())
                ->fromArray($salesOrderItemMetadataEntity->toArray(), true)
                ->setSuperAttributes($this->utilEncodingService->decodeJson($salesOrderItemMetadataEntity->getSuperAttributes(), true));
        }

        return $itemMetadataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $superAttributes
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata|null $salesOrderItemMetadataEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata
     */
    public function mapItemTransferToSalesOrderItemMetadataEntity(
        ItemTransfer $itemTransfer,
        array $superAttributes,
        ?SpySalesOrderItemMetadata $salesOrderItemMetadataEntity = null
    ): SpySalesOrderItemMetadata {
        $image = $this->determineImage($itemTransfer);

        $entity = $salesOrderItemMetadataEntity ?? new SpySalesOrderItemMetadata();
        $entity->setSuperAttributes($this->utilEncodingService->encodeJson($superAttributes));
        $entity->setImage($image);
        $entity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        return $entity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function determineImage(ItemTransfer $itemTransfer): ?string
    {
        $images = $itemTransfer->getImages();
        if (count($images) === 0) {
            return null;
        }

        return $images[0]->getExternalUrlSmall();
    }
}
