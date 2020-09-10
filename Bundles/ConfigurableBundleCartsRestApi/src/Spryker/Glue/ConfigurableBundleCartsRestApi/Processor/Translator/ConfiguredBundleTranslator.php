<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Translator;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface;

class ConfiguredBundleTranslator implements ConfiguredBundleTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function translateItemTransfer(ItemTransfer $itemTransfer, string $localeName): ItemTransfer
    {
        if (!$itemTransfer->getConfiguredBundle() || !$itemTransfer->getConfiguredBundleItem()) {
            return $itemTransfer;
        }

        $templateName = $itemTransfer->getConfiguredBundle()->getTemplate()->getName();
        $slotName = $itemTransfer->getConfiguredBundleItem()->getSlot()->getName();

        $translations = $this->glossaryStorageClient->translateBulk(array_filter([$templateName, $slotName]), $localeName);

        if ($templateName) {
            $itemTransfer->getConfiguredBundle()->getTemplate()->setName($translations[$templateName]);
        }

        if ($slotName) {
            $itemTransfer->getConfiguredBundleItem()->getSlot()->setName($translations[$slotName]);
        }

        return $itemTransfer;
    }
}
