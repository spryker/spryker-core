<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToUtilEncodingServiceInterface;
use Spryker\Client\ConfigurableBundleStorage\Expander\ConfigurableBundleTemplateImageStorageExpanderInterface;
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
     * @var \Spryker\Client\ConfigurableBundleStorage\Expander\ConfigurableBundleTemplateImageStorageExpanderInterface
     */
    protected $configurableBundleTemplateImageStorageExpander;

    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ConfigurableBundleStorage\Expander\ConfigurableBundleTemplateImageStorageExpanderInterface $configurableBundleTemplateImageStorageExpander
     * @param \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ConfigurableBundleStorageToStorageClientInterface $storageClient,
        ConfigurableBundleStorageToSynchronizationServiceInterface $synchronizationService,
        ConfigurableBundleTemplateImageStorageExpanderInterface $configurableBundleTemplateImageStorageExpander,
        ConfigurableBundleStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->configurableBundleTemplateImageStorageExpander = $configurableBundleTemplateImageStorageExpander;
        $this->utilEncodingService = $utilEncodingService;
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
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    public function getConfigurableBundleTemplateStorageCollection(
        ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
    ): array {
        $configurableBundleTemplateStorageFilterTransfer->requireLocaleName();

        $storageKeys = $this->prepareStorageKeys($configurableBundleTemplateStorageFilterTransfer);
        $configurableBundleTemplateStorageData = $this->storageClient->getMulti($storageKeys);

        if (!$configurableBundleTemplateStorageData) {
            return [];
        }

        $configurableBundleTemplateStorageTransfers = $this->mapToConfigurableBundleStorageTransfers($configurableBundleTemplateStorageData);

        return $this->configurableBundleTemplateImageStorageExpander->expandConfigurableBundleTemplatesStorageWithImageSets(
            $configurableBundleTemplateStorageTransfers,
            $configurableBundleTemplateStorageFilterTransfer->getLocaleName()
        );
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
        $configurableBundleTemplateStorageTransfer = $this->configurableBundleTemplateImageStorageExpander
            ->expandConfigurableBundleTemplateStorageWithImageSets($configurableBundleTemplateStorageTransfer, $localeName);

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
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
     *
     * @return string[]
     */
    protected function prepareStorageKeys(
        ConfigurableBundleTemplateStorageFilterTransfer $configurableBundleTemplateStorageFilterTransfer
    ): array {
        $storageKeys = [];
        $configurableBundleTemplateIds = $configurableBundleTemplateStorageFilterTransfer->getConfigurableBundleTemplateIds();

        if (!$configurableBundleTemplateIds) {
            return $storageKeys;
        }

        $keys = array_map('strval', $configurableBundleTemplateIds);

        foreach ($keys as $key) {
            $storageKeys[] = $this->generateKey($key);
        }

        return $storageKeys;
    }

    /**
     * @param array $configurableBundleTemplateStorageData
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[]
     */
    protected function mapToConfigurableBundleStorageTransfers(array $configurableBundleTemplateStorageData): array
    {
        $configurableBundleTemplateStorageTransfers = [];

        foreach ($configurableBundleTemplateStorageData as $configurableBundleTemplateStorageTransferData) {
            $configurableBundleTemplateStorageTransfers[] = $this->mapToConfigurableBundleStorage(
                $this->utilEncodingService->decodeJson($configurableBundleTemplateStorageTransferData, true) ?? []
            );
        }

        return $configurableBundleTemplateStorageTransfers;
    }
}
