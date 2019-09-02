<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem;
use Propel\Runtime\Collection\Collection;

class SalesOrderConfiguredBundleMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $bundleEntities
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function mapBundleEntityCollectionToBundleTransferCollection(
        Collection $bundleEntities
    ): SalesOrderConfiguredBundleCollectionTransfer {
        $bundleCollectionTransfer = new SalesOrderConfiguredBundleCollectionTransfer();

        foreach ($bundleEntities as $bundleEntity) {
            $bundleTransfer = $this->mapBundleEntityToBundleTransfer(
                $bundleEntity,
                new SalesOrderConfiguredBundleTransfer()
            );
            $bundleCollectionTransfer->addSalesOrderConfiguredBundle($bundleTransfer);
        }

        return $bundleCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $bundleTransfer
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle $bundleEntity
     *
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle
     */
    public function mapBundleTransferToBundleEntity(
        SalesOrderConfiguredBundleTransfer $bundleTransfer,
        SpySalesOrderConfiguredBundle $bundleEntity
    ): SpySalesOrderConfiguredBundle {
        $bundleEntity->fromArray($bundleTransfer->modifiedToArray());

        return $bundleEntity;
    }

    /**
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle $bundleEntity
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $bundleTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer
     */
    public function mapBundleEntityToBundleTransfer(
        SpySalesOrderConfiguredBundle $bundleEntity,
        SalesOrderConfiguredBundleTransfer $bundleTransfer
    ): SalesOrderConfiguredBundleTransfer {
        $bundleTransfer = $bundleTransfer->fromArray($bundleEntity->toArray(), true);

        $bundleTransfer->setSalesOrderConfiguredBundleItems(
            new ArrayObject($this->mapBundleEntityToBundleItemTransfers($bundleEntity))
        );

        return $bundleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $bundleItemTransfer
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem $bundleItemEntity
     *
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem
     */
    public function mapBundleItemTransferToBundleItemEntity(
        SalesOrderConfiguredBundleItemTransfer $bundleItemTransfer,
        SpySalesOrderConfiguredBundleItem $bundleItemEntity
    ): SpySalesOrderConfiguredBundleItem {
        $bundleItemEntity->fromArray($bundleItemTransfer->modifiedToArray());

        $bundleItemEntity
            ->setFkSalesOrderConfiguredBundle($bundleItemTransfer->getIdSalesOrderConfiguredBundle())
            ->setFkSalesOrderItem($bundleItemTransfer->getIdSalesOrderItem());

        return $bundleItemEntity;
    }

    /**
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem $bundleItemEntity
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer $bundleItemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer
     */
    public function mapBundleItemEntityToBundleItemTransfer(
        SpySalesOrderConfiguredBundleItem $bundleItemEntity,
        SalesOrderConfiguredBundleItemTransfer $bundleItemTransfer
    ): SalesOrderConfiguredBundleItemTransfer {
        $bundleItemTransfer = $bundleItemTransfer->fromArray($bundleItemEntity->toArray(), true);

        $bundleItemTransfer
            ->setIdSalesOrderConfiguredBundle($bundleItemEntity->getFkSalesOrderConfiguredBundle())
            ->setIdSalesOrderItem($bundleItemEntity->getFkSalesOrderItem());

        return $bundleItemTransfer;
    }

    /**
     * @param \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle $bundleEntity
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer[]
     */
    protected function mapBundleEntityToBundleItemTransfers(SpySalesOrderConfiguredBundle $bundleEntity): array
    {
        $bundleItemTransfers = [];

        foreach ($bundleEntity->getSpySalesOrderConfiguredBundleItems() as $bundleItemEntity) {
            $bundleItemTransfers[] = $this->mapBundleItemEntityToBundleItemTransfer($bundleItemEntity, new SalesOrderConfiguredBundleItemTransfer());
        }

        return $bundleItemTransfers;
    }
}
