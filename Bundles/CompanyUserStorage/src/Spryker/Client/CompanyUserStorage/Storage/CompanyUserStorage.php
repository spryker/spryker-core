<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage\Storage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface;
use Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;

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
     * @param \Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CompanyUserStorageToStorageClientInterface $storageClient,
        CompanyUserStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
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
        $mappingData = $this->storageClient->get($mappingKey);

        if (!$mappingData || !isset($mappingData[static::KEY_ID])) {
            return null;
        }

        $storageKey = $this->getStorageKey($mappingData[static::KEY_ID]);
        $companyUserStorageData = $this->storageClient->get($storageKey);

        return (new CompanyUserStorageTransfer())->fromArray($companyUserStorageData, true);
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
            ->getStorageKeyBuilder(CompanyUserStorageConfig::COMPANY_USER_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
