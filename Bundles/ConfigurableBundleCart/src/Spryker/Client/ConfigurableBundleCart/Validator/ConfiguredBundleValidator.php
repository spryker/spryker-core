<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;

class ConfiguredBundleValidator implements ConfiguredBundleValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return bool
     */
    public function validateCreateConfiguredBundleRequestTransfer(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): bool
    {
        if (!$createConfiguredBundleRequestTransfer->getConfiguredBundleRequest() || !$createConfiguredBundleRequestTransfer->getConfiguredBundleRequest()->getTemplateUuid()) {
            return false;
        }

        return $this->validateConfiguredBundleItemRequestTransfers(
            $createConfiguredBundleRequestTransfer->getConfiguredBundleItemRequests()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer[] $configuredBundleItemRequestTransfers
     *
     * @return bool
     */
    public function validateConfiguredBundleTemplateSlotCombination(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        ArrayObject $configuredBundleItemRequestTransfers
    ): bool {
        $configurableBundleTemplateSlotStorageUuids = $this->extractConfigurableBundleTemplateSlotStorageUuids($configurableBundleTemplateStorageTransfer);

        foreach ($configuredBundleItemRequestTransfers as $configuredBundleItemRequestTransfer) {
            if (!in_array($configuredBundleItemRequestTransfer->getSlotUuid(), $configurableBundleTemplateSlotStorageUuids, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer[] $configuredBundleItemRequestTransfers
     *
     * @return bool
     */
    protected function validateConfiguredBundleItemRequestTransfers(ArrayObject $configuredBundleItemRequestTransfers): bool
    {
        if (!$configuredBundleItemRequestTransfers->count()) {
            return false;
        }

        foreach ($configuredBundleItemRequestTransfers as $configuredBundleItemRequestTransfer) {
            if (!$configuredBundleItemRequestTransfer->getSku() || !$configuredBundleItemRequestTransfer->getSlotUuid()) {
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
