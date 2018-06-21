<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductBusinessUnitToSynchornizationServiceInterface;

class PriceProductMerchantRelationshipKeyGenerator implements PriceProductMerchantRelationshipKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductBusinessUnitToSynchornizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductBusinessUnitToSynchornizationServiceInterface $synchronizationService,
        PriceProductMerchantRelationshipStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $resourceName
     * @param int $idProduct
     * @param int $idBusinessUnit
     *
     * @return string
     */
    public function generateKey(string $resourceName, int $idProduct, int $idBusinessUnit): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->storeClient->getCurrentStore()->getName())
            ->setReference($idProduct . ':' . $idBusinessUnit);

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
