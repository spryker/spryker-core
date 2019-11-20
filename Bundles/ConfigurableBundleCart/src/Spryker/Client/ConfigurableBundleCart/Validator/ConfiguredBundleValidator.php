<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface;

class ConfiguredBundleValidator implements ConfiguredBundleValidatorInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     */
    public function __construct(ConfigurableBundleCartToConfigurableBundleStorageClientInterface $configurableBundleStorageClient)
    {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return bool
     */
    public function validateCreateConfiguredBundleRequestTransfer(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): bool
    {
        if (!$createConfiguredBundleRequestTransfer->getConfiguredBundleRequest() || !$createConfiguredBundleRequestTransfer->getConfiguredBundleItemRequests()->count()) {
            return false;
        }

        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorageByUuid(
            $createConfiguredBundleRequestTransfer->getConfiguredBundleRequest()->getTemplateUuid()
        );

        if (!$configurableBundleTemplateStorageTransfer) {
            return false;
        }

        return $this->validateConfiguredBundleTemplateSlotCombination(
            $configurableBundleTemplateStorageTransfer,
            $createConfiguredBundleRequestTransfer->getConfiguredBundleItemRequests()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer[] $configuredBundleItemRequestTransfers
     *
     * @return bool
     */
    protected function validateConfiguredBundleTemplateSlotCombination(
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
