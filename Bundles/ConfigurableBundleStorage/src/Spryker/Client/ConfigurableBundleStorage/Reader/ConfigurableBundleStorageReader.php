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
    protected const MAPPING_TYPE_UUID = 'uuid';
    protected const MAPPING_DELIMITER = ':';
    protected const MAPPING_DATA_KEY_ID = 'id';

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
        return $this->findStorageData((string)$idConfigurableBundleTemplate);
    }

    /**
     * @param string $configurableBundleTemplateUuid
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(string $configurableBundleTemplateUuid): ?ConfigurableBundleTemplateStorageTransfer
    {
        $mappingData = $this->storageClient->get(
            $this->generateKey(static::MAPPING_TYPE_UUID . static::MAPPING_DELIMITER . $configurableBundleTemplateUuid)
        );

        if (!$mappingData) {
            return null;
        }

        return $this->findStorageData($mappingData[static::MAPPING_DATA_KEY_ID]);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    protected function findStorageData(string $key): ?ConfigurableBundleTemplateStorageTransfer
    {
        $configurableBundleTemplateStorageTransferData = $this->storageClient->get(
            $this->generateKey($key)
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
     * @param string $reference
     *
     * @return string
     */
    protected function generateKey(string $reference): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
