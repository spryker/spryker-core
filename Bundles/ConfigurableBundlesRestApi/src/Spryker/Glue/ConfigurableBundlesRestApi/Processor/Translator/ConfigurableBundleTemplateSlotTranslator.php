<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;

class ConfigurableBundleTemplateSlotTranslator implements ConfigurableBundleTemplateSlotTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[] $configurableBundleTemplateSlotStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[]
     */
    public function translateConfigurableBundleTemplateSlotNames(
        array $configurableBundleTemplateSlotStorageTransfers,
        string $localeName
    ): array {
        $configurableBundleTemplateSlotNames = [];

        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $configurableBundleTemplateSlotNames[] = $configurableBundleTemplateSlotStorageTransfer->getName();
        }

        $translatedConfigurableBundleTemplateSlotNames = $this->glossaryStorageClient
            ->translateBulk($configurableBundleTemplateSlotNames, $localeName);

        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $configurableBundleTemplateSlotStorageTransfer->setName(
                $translatedConfigurableBundleTemplateSlotNames[$configurableBundleTemplateSlotStorageTransfer->getName()]
            );
        }

        return $configurableBundleTemplateSlotStorageTransfers;
    }
}
