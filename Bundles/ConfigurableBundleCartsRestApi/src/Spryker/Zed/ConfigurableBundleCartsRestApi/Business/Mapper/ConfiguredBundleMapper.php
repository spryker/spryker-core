<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Service\ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceInterface;

class ConfiguredBundleMapper implements ConfiguredBundleMapperInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Service\ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceInterface
     */
    protected $configurableBundleCartService;

    /**
     * @param \Spryker\Zed\ConfigurableBundleCartsRestApi\Dependency\Service\ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceInterface $configurableBundleCartService
     */
    public function __construct(ConfigurableBundleCartsRestApiToConfigurableBundleCartServiceInterface $configurableBundleCartService)
    {
        $this->configurableBundleCartService = $configurableBundleCartService;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCreateConfiguredBundleRequestToPersistentCartChange(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        $itemTransfers = [];
        $configuredBundleTransfer = $this->getSlimConfiguredBundleTransfer($createConfiguredBundleRequestTransfer->getConfiguredBundle());

        foreach ($createConfiguredBundleRequestTransfer->getItems() as $itemTransfer) {
            $itemTransfers[] = $this->getSlimItemTransfer($configuredBundleTransfer, $itemTransfer);
        }

        $persistentCartChangeTransfer->setItems(new ArrayObject($itemTransfers));

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapUpdateConfiguredBundleRequestToPersistentCartChange(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        $persistentCartChangeTransfer->requireQuote();

        $persistentCartChangeTransfer->setCustomer((new CustomerTransfer())
            ->fromArray($persistentCartChangeTransfer->getQuote()->getCustomer()->toArray()))
            ->setIdQuote($persistentCartChangeTransfer->getQuote()->getIdQuote());

        foreach ($persistentCartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle() || !$itemTransfer->getConfiguredBundleItem()) {
                continue;
            }

            if ($itemTransfer->getConfiguredBundle()->getGroupKey() !== $updateConfiguredBundleRequestTransfer->getGroupKey()) {
                continue;
            }

            $itemTransferToUpdate = (new ItemTransfer())
                ->fromArray($itemTransfer->toArray(false));

            if ($updateConfiguredBundleRequestTransfer->getQuantity() !== null) {
                $itemTransferToUpdate->setQuantity($itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot() * $updateConfiguredBundleRequestTransfer->getQuantity());
            }

            $persistentCartChangeTransfer->addItem($itemTransferToUpdate);
        }

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function getSlimConfiguredBundleTransfer(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        $configuredBundleTransfer = $this->configurableBundleCartService->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);

        return (new ConfiguredBundleTransfer())
            ->setGroupKey($configuredBundleTransfer->getGroupKey())
            ->setQuantity($configuredBundleTransfer->getQuantity())
            ->setTemplate(
                (new ConfigurableBundleTemplateTransfer())
                    ->setUuid($configuredBundleTransfer->getTemplate()->getUuid())
                    ->setName($configuredBundleTransfer->getTemplate()->getName())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getSlimItemTransfer(ConfiguredBundleTransfer $configuredBundleTransfer, ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer
            ->getConfiguredBundleItem()
                ->requireSlot()
                ->getSlot()
                    ->requireUuid();

        $configuredBundleItemTransfer = (new ConfiguredBundleItemTransfer())
            ->setQuantityPerSlot($itemTransfer->getConfiguredBundleItem()->getQuantityPerSlot())
            ->setSlot(
                (new ConfigurableBundleTemplateSlotTransfer())
                    ->setUuid($itemTransfer->getConfiguredBundleItem()->getSlot()->getUuid())
            );

        $itemTransfer
            ->setConfiguredBundle($configuredBundleTransfer)
            ->setConfiguredBundleItem($configuredBundleItemTransfer);

        return $itemTransfer;
    }
}
