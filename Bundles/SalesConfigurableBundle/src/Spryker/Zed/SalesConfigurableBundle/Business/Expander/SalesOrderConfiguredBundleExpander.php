<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleFilterTransfer;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleExpander implements SalesOrderConfiguredBundleExpanderInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithConfiguredBundles(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemIds = $this->getSalesOrderItemIdsFromOrder($orderTransfer);

        $salesOrderConfiguredBundleFilterTransfer = (new SalesOrderConfiguredBundleFilterTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        $salesOrderConfiguredBundleTransfers = $this->salesConfigurableBundleRepository
            ->getSalesOrderConfiguredBundleCollectionByFilter($salesOrderConfiguredBundleFilterTransfer)
            ->getSalesOrderConfiguredBundles();

        $salesOrderConfiguredBundleTransfers = $this->translateConfigurableBundleTemplateNames($salesOrderConfiguredBundleTransfers);

        $orderTransfer->setSalesOrderConfiguredBundles($salesOrderConfiguredBundleTransfers);

        $orderTransfer = $this->expandOrderItemsWithSalesOrderConfiguredBundleItems($orderTransfer, $salesOrderConfiguredBundleTransfers);

        return $orderTransfer;
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
                (new ConfigurableBundleTemplateTranslationTransfer())
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderItemsWithSalesOrderConfiguredBundleItems(
        OrderTransfer $orderTransfer,
        ArrayObject $salesOrderConfiguredBundleTransfers
    ): OrderTransfer {
        $salesOrderConfiguredBundleItemTransfers = $this->extractSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfers);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (array_key_exists($itemTransfer->getIdSalesOrderItem(), $salesOrderConfiguredBundleItemTransfers)) {
                $itemTransfer->setSalesOrderConfiguredBundleItem(
                    $salesOrderConfiguredBundleItemTransfers[$itemTransfer->getIdSalesOrderItem()]
                );
            }
        }

        return $orderTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer[] $salesOrderConfiguredBundleTransfers
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer[]
     */
    protected function extractSalesOrderConfiguredBundleItems(ArrayObject $salesOrderConfiguredBundleTransfers): array
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int[]
     */
    protected function getSalesOrderItemIdsFromOrder(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
