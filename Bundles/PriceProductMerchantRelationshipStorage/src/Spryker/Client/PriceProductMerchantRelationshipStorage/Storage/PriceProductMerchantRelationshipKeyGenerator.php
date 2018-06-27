<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipToSynchronizationServiceInterface;

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
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductMerchantRelationshipToSynchronizationServiceInterface $synchronizationService,
        PriceProductMerchantRelationshipStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $resourceName
     * @param int $idProduct
     * @param int $idCompanyBusinessUnit
     *
     * @return string
     */
    public function generateKey(string $resourceName, int $idProduct, int $idCompanyBusinessUnit): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->storeClient->getCurrentStore()->getName())
            ->setReference($idProduct . ':' . $idCompanyBusinessUnit);

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
