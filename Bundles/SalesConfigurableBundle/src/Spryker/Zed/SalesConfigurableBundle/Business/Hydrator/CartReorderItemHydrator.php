<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractorInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Service\SalesConfigurableBundleToConfigurableBundleServiceInterface;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractorInterface
     */
    protected ConfigurableBundleItemExtractorInterface $configurableBundleItemExtractor;

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Service\SalesConfigurableBundleToConfigurableBundleServiceInterface
     */
    protected SalesConfigurableBundleToConfigurableBundleServiceInterface $configurableBundleService;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractorInterface $configurableBundleItemExtractor
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Service\SalesConfigurableBundleToConfigurableBundleServiceInterface $configurableBundleService
     */
    public function __construct(
        ConfigurableBundleItemExtractorInterface $configurableBundleItemExtractor,
        SalesConfigurableBundleToConfigurableBundleServiceInterface $configurableBundleService
    ) {
        $this->configurableBundleItemExtractor = $configurableBundleItemExtractor;
        $this->configurableBundleService = $configurableBundleService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithConfigurableBundle = $this->configurableBundleItemExtractor->extractItemsWithConfigurableBundle(
            $cartReorderTransfer->getOrderItems(),
        );
        if ($itemsWithConfigurableBundle === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrder = $this->getItemTransfersIndexedByIdSalesOrder($cartReorderTransfer->getReorderItems());
        $configuredBundleTransfersIndexedByIdSalesConfigurableBundle = $this->createConfiguredBundleTransfersIndexedByIdSalesConfigurableBundle(
            $itemsWithConfigurableBundle,
        );
        foreach ($itemsWithConfigurableBundle as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrder[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $configuredBundleTransfersIndexedByIdSalesConfigurableBundle, $index);

                continue;
            }

            $this->addConfiguredBundle($itemTransfer, $reorderItemTransfer, $configuredBundleTransfersIndexedByIdSalesConfigurableBundle);
            $this->addConfiguredBundleItem($itemTransfer, $reorderItemTransfer);
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrder(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<int, \Generated\Shared\Transfer\ConfiguredBundleTransfer> $configuredBundleTransfersIndexedByIdSalesConfigurableBundle
     * @param int $index
     *
     * @return void
     */
    protected function addReorderItem(
        CartReorderTransfer $cartReorderTransfer,
        ItemTransfer $itemTransfer,
        array $configuredBundleTransfersIndexedByIdSalesConfigurableBundle,
        int $index
    ): void {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail());
        $reorderItemTransfer = $this->addConfiguredBundle($itemTransfer, $reorderItemTransfer, $configuredBundleTransfersIndexedByIdSalesConfigurableBundle);
        $reorderItemTransfer = $this->addConfiguredBundleItem($itemTransfer, $reorderItemTransfer);

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemsWithConfigurableBundle
     *
     * @return array<int, \Generated\Shared\Transfer\ConfiguredBundleTransfer>
     */
    protected function createConfiguredBundleTransfersIndexedByIdSalesConfigurableBundle(array $itemsWithConfigurableBundle): array
    {
        $indexedConfiguredBundleTransfers = [];
        foreach ($itemsWithConfigurableBundle as $itemTransfer) {
            $idSalesConfigurableBundle = $itemTransfer->getSalesOrderConfiguredBundleOrFail()->getIdSalesOrderConfiguredBundleOrFail();
            if (isset($indexedConfiguredBundleTransfers[$idSalesConfigurableBundle])) {
                continue;
            }

            $indexedConfiguredBundleTransfers[$idSalesConfigurableBundle] = $this->createConfiguredBundleTransfer($itemTransfer);
        }

        return $indexedConfiguredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function createConfiguredBundleTransfer(ItemTransfer $itemTransfer): ConfiguredBundleTransfer
    {
        $configuredBundleTransfer = (new ConfiguredBundleTransfer())
            ->setQuantity($itemTransfer->getSalesOrderConfiguredBundleOrFail()->getQuantityOrFail())
            ->setTemplate(
                (new ConfigurableBundleTemplateTransfer())
                    ->setUuid($itemTransfer->getSalesOrderConfiguredBundleOrFail()->getConfigurableBundleTemplateUuidOrFail())
                    ->setName($itemTransfer->getSalesOrderConfiguredBundleOrFail()->getName()),
            );

        return $this->configurableBundleService->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     * @param array<int, \Generated\Shared\Transfer\ConfiguredBundleTransfer> $configuredBundleTransfersIndexedByIdSalesConfigurableBundle
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addConfiguredBundle(
        ItemTransfer $itemTransfer,
        ItemTransfer $reorderItemTransfer,
        array $configuredBundleTransfersIndexedByIdSalesConfigurableBundle
    ): ItemTransfer {
        $idSalesConfiguredBundle = $itemTransfer->getSalesOrderConfiguredBundleOrFail()->getIdSalesOrderConfiguredBundleOrFail();
        $configuredBundleTransfer = $configuredBundleTransfersIndexedByIdSalesConfigurableBundle[$idSalesConfiguredBundle];

        $reorderItemConfiguredBundleTransfer = $reorderItemTransfer->getConfiguredBundle() ?? new ConfiguredBundleTransfer();
        $reorderItemConfiguredBundleTransfer->fromArray($configuredBundleTransfer->modifiedToArray());

        return $reorderItemTransfer->setConfiguredBundle($reorderItemConfiguredBundleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $reorderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addConfiguredBundleItem(ItemTransfer $itemTransfer, ItemTransfer $reorderItemTransfer): ItemTransfer
    {
        $configuredBundleItemTransfer = (new ConfiguredBundleItemTransfer())->setSlot(
            (new ConfigurableBundleTemplateSlotTransfer())
                ->setUuid($itemTransfer->getSalesOrderConfiguredBundleItemOrFail()->getConfigurableBundleTemplateSlotUuid()),
        );

        return $reorderItemTransfer->setConfiguredBundleItem($configuredBundleItemTransfer);
    }
}
