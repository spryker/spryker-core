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
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate, string $localeName): ?ConfigurableBundleTemplateStorageTransfer
    {
        return $this->findStorageData((string)$idConfigurableBundleTemplate, $localeName);
    }

    /**
     * @param string $configurableBundleTemplateUuid
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(
        string $configurableBundleTemplateUuid,
        string $localeName
    ): ?ConfigurableBundleTemplateStorageTransfer {
        $mappingData = $this->storageClient->get(
            $this->generateKey(static::MAPPING_TYPE_UUID . static::MAPPING_DELIMITER . $configurableBundleTemplateUuid)
        );

        if (!$mappingData) {
            return null;
        }

        return $this->findStorageData($mappingData[static::MAPPING_DATA_KEY_ID], $localeName);
    }

    /**
     * @param string $key
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    protected function findStorageData(string $key, string $localeName): ?ConfigurableBundleTemplateStorageTransfer
    {
        $configurableBundleTemplateStorageTransferData = $this->storageClient->get(
            $this->generateKey($key)
        );

        if (!$configurableBundleTemplateStorageTransferData) {
            return null;
        }

        $configurableBundleTemplateStorageTransfer = $this->mapToConfigurableBundleStorage($configurableBundleTemplateStorageTransferData);
        $configurableBundleTemplateStorageTransfer = $this->expandConfigurableBundleTemplateStorage($configurableBundleTemplateStorageTransfer, $localeName);

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

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer
     */
    protected function expandConfigurableBundleTemplateStorage(
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
}
