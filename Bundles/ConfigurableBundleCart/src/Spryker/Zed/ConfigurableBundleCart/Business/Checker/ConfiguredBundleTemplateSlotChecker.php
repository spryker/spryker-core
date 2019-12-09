<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business\Checker;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ConfiguredBundleValidationRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface;

class ConfiguredBundleTemplateSlotChecker implements ConfiguredBundleTemplateSlotCheckerInterface
{
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
        $groupedConfiguredBundleValidationRequestTransfers = $this->getGroupedConfiguredBundleValidationRequestTransfers($cartChangeTransfer);

        foreach ($groupedConfiguredBundleValidationRequestTransfers as $configuredBundleValidationRequestTransfer) {
            $isSlotCombinationValid = $this->configurableBundleCartRepository->verifyConfigurableBundleTemplateSlots(
                $configuredBundleValidationRequestTransfer->getTemplateUuid(),
                $configuredBundleValidationRequestTransfer->getSlotUuids()
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
     * @return \Generated\Shared\Transfer\ConfiguredBundleValidationRequestTransfer[]
     */
    protected function getGroupedConfiguredBundleValidationRequestTransfers(CartChangeTransfer $cartChangeTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ConfiguredBundleValidationRequestTransfer[] $groupedConfiguredBundleValidationRequestTransfers */
        $groupedConfiguredBundleValidationRequestTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isConfiguredBundleItem($itemTransfer)) {
                continue;
            }

            $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();
            $configuredBundleGroupKey = $configuredBundleTransfer->getGroupKey();

            if (!isset($groupedConfiguredBundleValidationRequestTransfers[$configuredBundleGroupKey])) {
                $groupedConfiguredBundleValidationRequestTransfers[$configuredBundleGroupKey] = (new ConfiguredBundleValidationRequestTransfer())
                    ->setTemplateUuid($configuredBundleTransfer->getTemplate()->getUuid());
            }

            $groupedConfiguredBundleValidationRequestTransfers[$configuredBundleGroupKey]->addSlotUuid(
                $itemTransfer->getConfiguredBundleItem()->getSlot()->getUuid()
            );
        }

        return $groupedConfiguredBundleValidationRequestTransfers;
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
