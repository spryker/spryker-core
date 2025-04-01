<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence\Mapper;

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
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct(OmsFacadeInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SspServiceTransfer
     */
    public function mapSalesOrderItemEntityToSspServiceTransfer(SpySalesOrderItem $salesOrderItemEntity): SspServiceTransfer
    {
        $serviceTransfer = new SspServiceTransfer();

        $serviceTransfer->setProductName($salesOrderItemEntity->getName());

        if ($salesOrderItemEntity->getCreatedAt()) {
            $serviceTransfer->setCreatedAt($salesOrderItemEntity->getCreatedAt()->format(static::DATE_TIME_FORMAT));
        }

        $serviceTransfer->setOrderReference($salesOrderItemEntity->getOrder()->getOrderReference());
        $serviceTransfer->setIdSalesOrder($salesOrderItemEntity->getOrder()->getIdSalesOrder());

        if ($salesOrderItemEntity->getMetadatas()->count()) {
            $serviceTransfer->setScheduledAt($salesOrderItemEntity->getMetadatas()->getFirst()->getScheduledAt(static::DATE_TIME_FORMAT));
        }

        $serviceTransfer->setStateDisplayName($this->omsFacade->getStateDisplayName($salesOrderItemEntity));

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
