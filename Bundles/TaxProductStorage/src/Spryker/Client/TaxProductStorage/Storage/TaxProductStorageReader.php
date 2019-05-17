<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Spryker\Client\TaxProductStorage\Dependency\Client\TaxProductStorageToStorageClientInterface;
use Spryker\Client\TaxProductStorage\Dependency\Service\TaxProductStorageToSynchronizationServiceInterface;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;

class TaxProductStorageReader implements TaxProductStorageReaderInterface
{
    /**
     * @var \Spryker\Client\TaxProductStorage\Dependency\Client\TaxProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\TaxProductStorage\Dependency\Service\TaxProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\TaxProductStorage\Dependency\Client\TaxProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\TaxProductStorage\Dependency\Service\TaxProductStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        TaxProductStorageToStorageClientInterface $storageClient,
        TaxProductStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorageByProductAbstractSku(string $productAbstractSku): ?TaxProductStorageTransfer
    {
        $storageKey = $this->generateKey($productAbstractSku);
        $taxProductStorageData = $this->storageClient->get($storageKey);

        if (!$taxProductStorageData) {
            return null;
        }

        return (new TaxProductStorageTransfer())->fromArray($taxProductStorageData, true);
    }

    /**
     * @param string $productAbstractSku
     *
     * @return string
     */
    protected function generateKey(string $productAbstractSku): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($productAbstractSku);

        return $this->synchronizationService
            ->getStorageKeyBuilder(TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
