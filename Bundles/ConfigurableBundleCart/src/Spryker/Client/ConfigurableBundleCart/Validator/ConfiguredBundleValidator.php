<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

class ConfiguredBundleValidator implements ConfiguredBundleValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function validateConfiguredBundleTemplateSlotCombination(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        ArrayObject $itemTransfers
    ): bool {
        $configurableBundleTemplateSlotStorageUuids = $this->extractConfigurableBundleTemplateSlotStorageUuids($configurableBundleTemplateStorageTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->requireConfiguredBundleItem()
                ->getConfiguredBundleItem()
                    ->requireSlot();

            if (!in_array($itemTransfer->getConfiguredBundleItem()->getSlot()->getUuid(), $configurableBundleTemplateSlotStorageUuids, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return string[]
     */
    protected function extractConfigurableBundleTemplateSlotStorageUuids(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer): array
    {
        $uuids = [];

        foreach ($configurableBundleTemplateStorageTransfer->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
            $uuids[] = $configurableBundleTemplateSlotStorageTransfer->getUuid();
        }

        return $uuids;
    }
}
