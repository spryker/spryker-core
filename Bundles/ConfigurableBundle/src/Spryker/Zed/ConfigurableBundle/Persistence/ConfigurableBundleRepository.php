<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleCollectionTransfer
     */
    public function getSalesOrderConfiguredBundleCollectionByFilter(
        ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
    ): SalesOrderConfiguredBundleCollectionTransfer {
        $salesOrderConfiguredBundleQuery = $this->getFactory()
            ->getSalesOrderConfiguredBundlePropelQuery()
            ->joinWithSpySalesOrderConfiguredBundleItem();

        $salesOrderConfiguredBundleQuery = $this->setSalesOrderConfiguredBundleFilters(
            $salesOrderConfiguredBundleQuery,
            $configuredBundleFilterTransfer
        );

        return $this->getFactory()
            ->createSalesOrderConfiguredBundleMapper()
            ->mapBundleEntityCollectionToBundleTransferCollection($salesOrderConfiguredBundleQuery->find());
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery $salesOrderConfiguredBundleQuery
     * @param \Generated\Shared\Transfer\ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery
     */
    protected function setSalesOrderConfiguredBundleFilters(
        SpySalesOrderConfiguredBundleQuery $salesOrderConfiguredBundleQuery,
        ConfiguredBundleFilterTransfer $configuredBundleFilterTransfer
    ): SpySalesOrderConfiguredBundleQuery {
        if ($configuredBundleFilterTransfer->getTemplate() && $configuredBundleFilterTransfer->getTemplate()->getUuid()) {
            $salesOrderConfiguredBundleQuery->filterByConfigurableBundleTemplateUuid($configuredBundleFilterTransfer->getTemplate()->getUuid());
        }

        if ($configuredBundleFilterTransfer->getSlot() && $configuredBundleFilterTransfer->getSlot()->getUuid()) {
            $salesOrderConfiguredBundleQuery
                ->useSpySalesOrderConfiguredBundleItemQuery()
                    ->filterByConfigurableBundleTemplateSlotUuid($configuredBundleFilterTransfer->getSlot()->getUuid())
                ->endUse();
        }

        return $salesOrderConfiguredBundleQuery;
    }
}
