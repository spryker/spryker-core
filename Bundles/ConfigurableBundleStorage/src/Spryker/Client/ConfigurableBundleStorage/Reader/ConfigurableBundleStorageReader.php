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
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ConfigurableBundleStorageToStorageClientInterface $storageClient,
        ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
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

        return $this->mapToConfigurableBundleStorage($configurableBundleTemplateStorageTransferData);
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
}
