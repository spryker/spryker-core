<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface;

class ConfigurableBundleTemplateImageStorageExpander implements ConfigurableBundleTemplateImageStorageExpanderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface
     */
    protected $configurableBundleTemplateImageStorageReader;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface $configurableBundleTemplateImageStorageReader
     */
    public function __construct(ConfigurableBundleTemplateImageStorageReaderInterface $configurableBundleTemplateImageStorageReader)
    {
        $this->configurableBundleTemplateImageStorageReader = $configurableBundleTemplateImageStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer
     */
    public function expandConfigurableBundleTemplateStorageWithImageSets(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        string $localeName
    ): ConfigurableBundleTemplateStorageTransfer {
        $configurableBundleTemplateImageStorageTransfer = $this->configurableBundleTemplateImageStorageReader
            ->findConfigurableBundleTemplateImageStorage($configurableBundleTemplateStorageTransfer->getIdConfigurableBundleTemplate(), $localeName);

        if ($configurableBundleTemplateImageStorageTransfer) {
            $configurableBundleTemplateStorageTransfer->setImageSets($configurableBundleTemplateImageStorageTransfer->getImageSets());
        }

        return $configurableBundleTemplateStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function expandConfigurableBundleTemplatesStorageWithImageSets(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): array {
        $configurableBundleTemplateIds = $this->getConfigurableBundleTemplateIds($configurableBundleTemplateStorageTransfers);
        $configurableBundleTemplateImageStorageTransfers = $this->configurableBundleTemplateImageStorageReader
            ->getBulkConfigurableBundleTemplateImageStorage($configurableBundleTemplateIds, $localeName);

        $mappedConfigurableBundleTemplateImageStorageTransfers = $this->mapConfigurableBundleTemplateImageStorageTransfers($configurableBundleTemplateImageStorageTransfers);

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $configurableBundleTemplateImageStorageTransfer = $mappedConfigurableBundleTemplateImageStorageTransfers[$configurableBundleTemplateStorageTransfer->getIdConfigurableBundleTemplate()] ?? null;

            if (!$configurableBundleTemplateImageStorageTransfer) {
                continue;
            }

            $configurableBundleTemplateStorageTransfer->setImageSets($configurableBundleTemplateImageStorageTransfer->getImageSets());
        }

        return $configurableBundleTemplateStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     *
     * @return int[]
     */
    protected function getConfigurableBundleTemplateIds(array $configurableBundleTemplateStorageTransfers): array
    {
        $configurableBundleTemplateIds = [];

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $configurableBundleTemplateIds[] = $configurableBundleTemplateStorageTransfer->getIdConfigurableBundleTemplate();
        }

        return $configurableBundleTemplateIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer[] $configurableBundleTemplateImageStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer[]
     */
    protected function mapConfigurableBundleTemplateImageStorageTransfers(array $configurableBundleTemplateImageStorageTransfers): array
    {
        $mappedConfigurableBundleTemplateImageStorageTransfers = [];

        foreach ($configurableBundleTemplateImageStorageTransfers as $configurableBundleTemplateImageStorageTransfer) {
            $mappedConfigurableBundleTemplateImageStorageTransfers[$configurableBundleTemplateImageStorageTransfer->getIdConfigurableBundleTemplate()]
                = $configurableBundleTemplateImageStorageTransfer;
        }

        return $mappedConfigurableBundleTemplateImageStorageTransfers;
    }
}
