<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleWriter implements SalesOrderConfiguredBundleWriterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
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
            $salesOrderConfiguredBundleTransfer = $this->configurableBundleEntityManager
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
        foreach ($salesOrderConfiguredBundleTransfer->getItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle());
            $this->configurableBundleEntityManager->createSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function mapSalesOrderConfiguredBundles(QuoteTransfer $quoteTransfer): array
    {
        /** @var \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers */
        $salesOrderConfiguredBundleTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle()) {
                continue;
            }

            $configurableBundleTemplateTransfer = $itemTransfer->getConfiguredBundle()->getTemplate();

            $salesOrderConfiguredBundleTransfer = (new SalesOrderConfiguredBundleTransfer())
                ->setConfigurableBundleTemplateUuid($configurableBundleTemplateTransfer->getUuid())
                ->setName($configurableBundleTemplateTransfer->getName());

            if (!isset($salesOrderConfiguredBundleTransfers[$configurableBundleTemplateTransfer->getUuid()])) {
                $salesOrderConfiguredBundleTransfers[$configurableBundleTemplateTransfer->getUuid()] = $salesOrderConfiguredBundleTransfer;
            }

            $salesOrderConfiguredBundleItemTransfer = (new SalesOrderConfiguredBundleItemTransfer())
                ->setConfigurableBundleTemplateSlotUuid($itemTransfer->getConfiguredBundle()->getSlot()->getUuid())
                ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

            $salesOrderConfiguredBundleTransfers[$configurableBundleTemplateTransfer->getUuid()]
                ->addItem($salesOrderConfiguredBundleItemTransfer);
        }

        return $salesOrderConfiguredBundleTransfers;
    }
}
