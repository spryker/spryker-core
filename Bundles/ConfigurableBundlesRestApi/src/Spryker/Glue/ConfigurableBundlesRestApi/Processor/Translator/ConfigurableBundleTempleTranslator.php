<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;

class ConfigurableBundleTempleTranslator implements ConfigurableBundleTempleTranslatorInterface
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
    public function translateConfigurableBundleTemplateNames(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): array {
        $configurableBundleTemplateNames = [];

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $configurableBundleTemplateNames[] = $configurableBundleTemplateStorageTransfer->getName();
        }

        $translatedConfigurableBundleTemplateNames = $this->glossaryStorageClient
            ->translateBulk($configurableBundleTemplateNames, $localeName);

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $configurableBundleTemplateStorageTransfer->setName(
                $translatedConfigurableBundleTemplateNames[$configurableBundleTemplateStorageTransfer->getName()]
            );
        }

        return $configurableBundleTemplateStorageTransfers;
    }
}
