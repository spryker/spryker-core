<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Checker;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface;

class ConfiguredBundleTemplateSlotChecker implements ConfiguredBundleTemplateSlotCheckerInterface
{
    protected const KEY_CONFIGURABLE_BUNDLE_TEMPLATE_UUID = 'configurableBundleTemplateUuid';
    protected const KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUIDS = 'configurableBundleTemplateSlotUuids';

    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED = 'configured_bundle_cart.error.configured_bundle_cannot_be_added';

    /**
     * @var \Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface
     */
    protected $configurableBundleCartRepository;

    /**
     * @param \Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface $configurableBundleCartRepository
     */
    public function __construct(ConfigurableBundleCartRepositoryInterface $configurableBundleCartRepository)
    {
        $this->configurableBundleCartRepository = $configurableBundleCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkConfiguredBundleTemplateSlotCombination(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $messageTransfers = new ArrayObject();
        $configuredBundlesData = $this->getConfiguredBundlesData($cartChangeTransfer);

        foreach ($configuredBundlesData as $configuredBundleData) {
            $isSlotCombinationValid = $this->configurableBundleCartRepository->verifyConfigurableBundleTemplateSlots(
                $configuredBundleData[static::KEY_CONFIGURABLE_BUNDLE_TEMPLATE_UUID],
                $configuredBundleData[static::KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUIDS]
            );

            if (!$isSlotCombinationValid) {
                $messageTransfers->append((new MessageTransfer())->setValue(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_CANNOT_BE_ADDED));
            }
        }

        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess($messageTransfers->count() === 0)
            ->setMessages($messageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getConfiguredBundlesData(CartChangeTransfer $cartChangeTransfer): array
    {
        $configuredBundlesData = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isConfiguredBundleItem($itemTransfer)) {
                continue;
            }

            $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();
            $configuredBundleGroupKey = $configuredBundleTransfer->getGroupKey();

            if (!isset($configuredBundlesData[$configuredBundleGroupKey])) {
                $configuredBundlesData[$configuredBundleGroupKey] = [
                    static::KEY_CONFIGURABLE_BUNDLE_TEMPLATE_UUID => $configuredBundleTransfer->getTemplate()->getUuid(),
                ];
            }

            $configuredBundlesData[$configuredBundleGroupKey][static::KEY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_UUIDS][] = $itemTransfer->getConfiguredBundleItem()->getSlot()->getUuid();
        }

        return $configuredBundlesData;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isConfiguredBundleItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getConfiguredBundleItem() && $itemTransfer->getConfiguredBundle();
    }
}
