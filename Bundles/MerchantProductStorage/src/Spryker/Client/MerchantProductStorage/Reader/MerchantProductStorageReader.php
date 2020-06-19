<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage\Reader;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToStorageClientInterface;
use Spryker\Client\MerchantProductStorage\Dependency\Service\MerchantProductStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper;
use Spryker\Shared\MerchantProductStorage\MerchantProductStorageConfig;

class MerchantProductStorageReader implements MerchantProductStorageReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\MerchantProductStorage\Dependency\Service\MerchantProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper
     */
    protected $merchantProductStorageMapper;

    /**
     * @param \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantProductStorage\Dependency\Service\MerchantProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper $merchantProductStorageMapper
     */
    public function __construct(
        MerchantProductStorageToStorageClientInterface $storageClient,
        MerchantProductStorageToSynchronizationServiceInterface $synchronizationService,
        MerchantProductStorageMapper $merchantProductStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->merchantProductStorageMapper = $merchantProductStorageMapper;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer|null
     */
    public function findOne(int $idProductAbstract): ?MerchantProductStorageTransfer
    {
        $merchantProductKey = $this->generateKey((string)$idProductAbstract, MerchantProductStorageConfig::RESOURCE_MERCHANT_PRODUCT_ABSTRACT_NAME);
        $merchantProductStorageData = $this->storageClient->get($merchantProductKey);

        if (!$merchantProductStorageData) {
            return null;
        }
        unset($merchantProductStorageData['_timestamp']);

        return $this->merchantProductStorageMapper->mapMerchantProductStorageDataToMerchantProductStorageTransfer(
            $merchantProductStorageData,
            new MerchantProductStorageTransfer()
        );
    }

    /**
     * @param string $keyName
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateKey(string $keyName, string $resourceName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
