<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTranslationTransfer;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface
     */
    protected $salesConfigurableBundleRepository;

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository,
        SalesConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->salesConfigurableBundleRepository = $salesConfigurableBundleRepository;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithSalesOrderConfiguredBundles(array $itemTransfers): array
    {
        $salesOrderConfiguredBundleTransfers = $this->getSalesOrderConfiguredBundleTransfers($this->getSalesOrderItemIds($itemTransfers));
        $salesOrderConfiguredBundleItemTransfers = $this->indexSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            if (!isset($salesOrderConfiguredBundleItemTransfers[$itemTransfer->getIdSalesOrderItem()])) {
                continue;
            }

            $salesOrderConfiguredBundleItemTransfer = $salesOrderConfiguredBundleItemTransfers[$itemTransfer->getIdSalesOrderItem()];
            $salesOrderConfiguredBundleTransfer = (new SalesOrderConfiguredBundleTransfer())
                ->fromArray($salesOrderConfiguredBundleTransfers[$salesOrderConfiguredBundleItemTransfer->getIdSalesOrderConfiguredBundle()]->toArray())
                ->setSalesOrderConfiguredBundleItems(new ArrayObject());

            $itemTransfer->setSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer)
                ->setSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function getSalesOrderConfiguredBundleTransfers(array $salesOrderItemIds): array
    {
        $salesOrderConfiguredBundleTransfers = $this->salesConfigurableBundleRepository
            ->getSalesOrderConfiguredBundleCollectionByFilter(
                (new SalesOrderConfiguredBundleFilterTransfer())
                    ->setSalesOrderItemIds($salesOrderItemIds)
            )
            ->getSalesOrderConfiguredBundles();
        $salesOrderConfiguredBundleTransfers = $this->translateConfigurableBundleTemplateNames($salesOrderConfiguredBundleTransfers);

        return $this->indexSalesOrderConfiguredBundleTransfersById($salesOrderConfiguredBundleTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getIdSalesOrderItem()) {
                $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
            }
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function translateConfigurableBundleTemplateNames(ArrayObject $salesOrderConfiguredBundleTransfers): ArrayObject
    {
        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            $salesOrderConfiguredBundleTransfer->addTranslation(
                (new SalesOrderConfiguredBundleTranslationTransfer())
                    ->setName(
                        $this->glossaryFacade->translate(
                            $salesOrderConfiguredBundleTransfer->getName()
                        )
                    )
            );
        }

        return $salesOrderConfiguredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer[]
     */
    protected function indexSalesOrderConfiguredBundleItems(array $salesOrderConfiguredBundleTransfers): array
    {
        $salesOrderConfiguredBundleItemTransfers = [];

        foreach ($salesOrderConfiguredBundleTransfers as $configuredBundleTransfer) {
            foreach ($configuredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
                $idSalesOrderItem = $salesOrderConfiguredBundleItemTransfer->getIdSalesOrderItem();
                $salesOrderConfiguredBundleItemTransfers[$idSalesOrderItem] = $salesOrderConfiguredBundleItemTransfer;
            }
        }

        return $salesOrderConfiguredBundleItemTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[]
     */
    protected function indexSalesOrderConfiguredBundleTransfersById(ArrayObject $salesOrderConfiguredBundleTransfers): array
    {
        $indexedSalesOrderConfiguredBundleTransfers = [];

        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            $indexedSalesOrderConfiguredBundleTransfers[$salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle()] =
                $salesOrderConfiguredBundleTransfer;
        }

        return $indexedSalesOrderConfiguredBundleTransfers;
    }
}
