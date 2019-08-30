<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface;

class SalesOrderConfiguredBundleWriter implements SalesOrderConfiguredBundleWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface
     */
    protected $salesConfigurableBundleEntityManager;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager
     */
    public function __construct(SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager)
    {
        $this->salesConfigurableBundleEntityManager = $salesConfigurableBundleEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $salesOrderConfiguredBundleTransfers = $this->mapSalesOrderConfiguredBundles($quoteTransfer);

        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            $salesOrderConfiguredBundleTransfer = $this->salesConfigurableBundleEntityManager
                ->createSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer);

            $this->saveSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return void
     */
    protected function saveSalesOrderConfiguredBundleItems(SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer): void
    {
        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle());
            $this->salesConfigurableBundleEntityManager->createSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function mapSalesOrderConfiguredBundles(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderConfiguredBundleTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundleItem() && $itemTransfer->getConfiguredBundle()) {
                $salesOrderConfiguredBundleTransfers = $this->mapSalesOrderConfiguredBundle(
                    $itemTransfer,
                    $salesOrderConfiguredBundleTransfers
                );
            }
        }

        return $salesOrderConfiguredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function mapSalesOrderConfiguredBundle(ItemTransfer $itemTransfer, array $salesOrderConfiguredBundleTransfers): array
    {
        $configuredBundleItemTransfer = $itemTransfer->getConfiguredBundleItem();
        $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();

        $configuredBundleItemTransfer
            ->requireSlot()
            ->getSlot()
                ->requireUuid();

        $configuredBundleTransfer
            ->requireGroupKey()
            ->requireQuantity()
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid()
                ->requireName();

        $salesOrderConfiguredBundleTransfer = (new SalesOrderConfiguredBundleTransfer())
            ->setConfigurableBundleTemplateUuid($configuredBundleTransfer->getTemplate()->getUuid())
            ->setName($configuredBundleTransfer->getTemplate()->getName())
            ->setQuantity($configuredBundleTransfer->getQuantity());

        if (!isset($salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()])) {
            $salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()] = $salesOrderConfiguredBundleTransfer;
        }

        $salesOrderConfiguredBundleItemTransfer = (new SalesOrderConfiguredBundleItemTransfer())
            ->setConfigurableBundleTemplateSlotUuid($configuredBundleItemTransfer->getSlot()->getUuid())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        $salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()]
            ->addSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);

        return $salesOrderConfiguredBundleTransfers;
    }
}
