<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspServiceTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;

class SspServiceMapper
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const COMPANY_NAME_VIRTUAL_COLUMN = 'company_name';

    public function __construct(protected OmsFacadeInterface $omsFacade)
    {
    }

    public function mapSalesOrderItemEntityToSspServiceTransfer(SpySalesOrderItem $salesOrderItemEntity): SspServiceTransfer
    {
        $serviceTransfer = (new SspServiceTransfer())->setOrder((new OrderTransfer()));

        $serviceTransfer->setProductName($salesOrderItemEntity->getName());

        if ($salesOrderItemEntity->getCreatedAt()) {
            $serviceTransfer->setCreatedAt($salesOrderItemEntity->getCreatedAt()->format(static::DATE_TIME_FORMAT));
        }

        $serviceTransfer->getOrderOrFail()->setOrderReference($salesOrderItemEntity->getOrder()->getOrderReference());
        $serviceTransfer->getOrderOrFail()->setIdSalesOrder($salesOrderItemEntity->getOrder()->getIdSalesOrder());

        if ($salesOrderItemEntity->getMetadatas()->count()) {
            $serviceTransfer->setScheduledAt($salesOrderItemEntity->getMetadatas()->getFirst()->getScheduledAt(static::DATE_TIME_FORMAT));
        }

        $serviceTransfer->getOrderOrFail()->setFirstName($salesOrderItemEntity->getOrder()->getFirstName());
        $serviceTransfer->getOrderOrFail()->setLastName($salesOrderItemEntity->getOrder()->getLastName());
        if ($salesOrderItemEntity->hasVirtualColumn(static::COMPANY_NAME_VIRTUAL_COLUMN)) {
            $serviceTransfer->getOrderOrFail()->setCompanyName($salesOrderItemEntity->getVirtualColumn(static::COMPANY_NAME_VIRTUAL_COLUMN));
        }

        $serviceTransfer->setStateDisplayName($this->omsFacade->getStateDisplayName($salesOrderItemEntity));
        $serviceTransfer->setStateName($salesOrderItemEntity->getState()->getName());

        foreach ($salesOrderItemEntity->getSalesOrderItemSspAssets() as $salesOrderItemSspAssetEntity) {
            $serviceTransfer->addSspAsset((new SspAssetTransfer())->setReference($salesOrderItemSspAssetEntity->getReference()));
        }

        return $serviceTransfer;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return array<\Generated\Shared\Transfer\SspServiceTransfer>
     */
    public function mapSalesOrderItemEntitiesToSspServiceTransfers(array $salesOrderItemEntities): array
    {
        $serviceTransfers = [];

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $serviceTransfers[] = $this->mapSalesOrderItemEntityToSspServiceTransfer($salesOrderItemEntity);
        }

        return $serviceTransfers;
    }
}
