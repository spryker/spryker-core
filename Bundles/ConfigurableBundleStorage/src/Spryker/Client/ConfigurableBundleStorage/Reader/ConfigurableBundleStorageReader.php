<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;

class ConfigurableBundleStorageReader implements ConfigurableBundleStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface
     */
    protected $configurableBundleTemplateImageStorageReader;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface $configurableBundleTemplateImageStorageReader
     */
    public function __construct(
        ConfigurableBundleStorageToStorageClientInterface $storageClient,
        ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ConfigurableBundleTemplateImageStorageReaderInterface $configurableBundleTemplateImageStorageReader
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->configurableBundleTemplateImageStorageReader = $configurableBundleTemplateImageStorageReader;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer
    {
        $configurableBundleTemplateStorageTransferData = $this->storageClient->get(
            $this->generateKey($idConfigurableBundleTemplate)
        );

        if (!$configurableBundleTemplateStorageTransferData) {
            return null;
        }

        $configurableBundleTemplateStorageTransfer = $this->mapToConfigurableBundleStorage($configurableBundleTemplateStorageTransferData);
        $configurableBundleTemplateStorageTransfer = $this->expandConfigurableBundleTemplateStorage($configurableBundleTemplateStorageTransfer);

        return $configurableBundleTemplateStorageTransfer;
    }

    /**
     * @param array $configurableBundleTemplateStorageData
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer
     */
    protected function mapToConfigurableBundleStorage(array $configurableBundleTemplateStorageData): ConfigurableBundleTemplateStorageTransfer
    {
        return (new ConfigurableBundleTemplateStorageTransfer())
            ->fromArray($configurableBundleTemplateStorageData, true);
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return string
     */
    protected function generateKey(int $idConfigurableBundleTemplate): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference((string)$idConfigurableBundleTemplate);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer
     */
    protected function expandConfigurableBundleTemplateStorage(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): ConfigurableBundleTemplateStorageTransfer {
        $configurableBundleTemplateImageStorageTransfer = $this->configurableBundleTemplateImageStorageReader
            ->findConfigurableBundleTemplateImageStorage($configurableBundleTemplateStorageTransfer->getIdConfigurableBundleTemplate());

        if ($configurableBundleTemplateImageStorageTransfer) {
            $configurableBundleTemplateStorageTransfer->setImageSets($configurableBundleTemplateImageStorageTransfer->getImageSets());
        }

        return $configurableBundleTemplateStorageTransfer;
    }
}
