<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;

class ConfigurableBundleTranslator implements ConfigurableBundleTranslatorInterface
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
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function translateConfigurableBundleTemplateStorageTransfers(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): array {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromConfigurableBundleTemplateStorageTransfers(
            $configurableBundleTemplateStorageTransfers
        );

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToConfigurableBundleTemplateStorageTransfers(
            $configurableBundleTemplateStorageTransfers,
            $translations
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromConfigurableBundleTemplateStorageTransfers(
        array $configurableBundleTemplateStorageTransfers
    ): array {
        $glossaryStorageKeys = [];
        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $glossaryStorageKeys[] = $configurableBundleTemplateStorageTransfer->getName();

            foreach ($configurableBundleTemplateStorageTransfer->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
                $glossaryStorageKeys[] = $configurableBundleTemplateSlotStorageTransfer->getName();
            }

            foreach ($configurableBundleTemplateStorageTransfer->getImageSets() as $productImageSetStorageTransfer) {
                $glossaryStorageKeys[] = $productImageSetStorageTransfer->getName();
            }
        }

        return array_unique(array_filter($glossaryStorageKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string[] $translations
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    protected function setTranslationsToConfigurableBundleTemplateStorageTransfers(
        array $configurableBundleTemplateStorageTransfers,
        array $translations
    ): array {
        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            if (isset($translations[$configurableBundleTemplateStorageTransfer->getName()])) {
                $configurableBundleTemplateStorageTransfer->setName(
                    $translations[$configurableBundleTemplateStorageTransfer->getName()]
                );
            }

            foreach ($configurableBundleTemplateStorageTransfer->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
                if (isset($translations[$configurableBundleTemplateSlotStorageTransfer->getName()])) {
                    $configurableBundleTemplateSlotStorageTransfer->setName(
                        $translations[$configurableBundleTemplateSlotStorageTransfer->getName()]
                    );
                }
            }

            foreach ($configurableBundleTemplateStorageTransfer->getImageSets() as $productImageSetStorageTransfer) {
                if (isset($translations[$productImageSetStorageTransfer->getName()])) {
                    $productImageSetStorageTransfer->setName(
                        $translations[$productImageSetStorageTransfer->getName()]
                    );
                }
            }
        }

        return $configurableBundleTemplateStorageTransfers;
    }
}
