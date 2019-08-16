<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage\Storage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface;
use Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig as SharedCompanyUserStorageConfig;

class CompanyUserStorage implements CompanyUserStorageInterface
{
    protected const KEY_ID = 'id';

    /**
     * @var \Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CompanyUserStorage\CompanyUserStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CompanyUserStorage\CompanyUserStorageConfig $config
     */
    public function __construct(
        CompanyUserStorageToStorageClientInterface $storageClient,
        CompanyUserStorageToSynchronizationServiceInterface $synchronizationService,
        CompanyUserStorageConfig $config
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->config = $config;
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer
    {
        $reference = $mappingType . ':' . $identifier;
        $mappingKey = $this->getStorageKey($reference);

        return $this->findStorageDataByMappingKey($mappingKey);
    }

    /**
     * @param string $reference
     *
     * @return string
     */
    protected function getStorageKey(string $reference): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedCompanyUserStorageConfig::COMPANY_USER_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $mappingKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    protected function findStorageDataByMappingKey(string $mappingKey): ?CompanyUserStorageTransfer
    {
        $storageData = $this->storageClient->get($mappingKey);

        if ($this->config->isSendingToQueue()) {
            $storageData = $this->resolveMappingData($storageData);
        }

        if (!$storageData) {
            return null;
        }

        return (new CompanyUserStorageTransfer())->fromArray($storageData, true);
    }

    /**
     * @param mixed $mappingData
     *
     * @return array|null
     */
    protected function resolveMappingData($mappingData): ?array
    {
        if (!$mappingData || !isset($mappingData[static::KEY_ID])) {
            return null;
        }

        $storageKey = $this->getStorageKey($mappingData[static::KEY_ID]);

        return $this->storageClient->get($storageKey);
    }
}
