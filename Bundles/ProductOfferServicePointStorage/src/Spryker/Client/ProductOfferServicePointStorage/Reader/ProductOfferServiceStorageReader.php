<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Reader;

use Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStorageClientInterface;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOfferServicePointStorage\Generator\ProductOfferServiceStorageKeyGeneratorInterface;
use Spryker\Client\ProductOfferServicePointStorage\Mapper\ProductOfferServiceStorageMapperInterface;

class ProductOfferServiceStorageReader implements ProductOfferServiceStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface
     */
    protected ProductOfferServicePointStorageToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStorageClientInterface
     */
    protected ProductOfferServicePointStorageToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Generator\ProductOfferServiceStorageKeyGeneratorInterface
     */
    protected ProductOfferServiceStorageKeyGeneratorInterface $productOfferServiceStorageKeyGenerator;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToUtilEncodingServiceInterface
     */
    protected ProductOfferServicePointStorageToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Mapper\ProductOfferServiceStorageMapperInterface
     */
    protected ProductOfferServiceStorageMapperInterface $productOfferServiceStorageMapper;

    /**
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductOfferServicePointStorage\Generator\ProductOfferServiceStorageKeyGeneratorInterface $productOfferServiceStorageKeyGenerator
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Service\ProductOfferServicePointStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ProductOfferServicePointStorage\Mapper\ProductOfferServiceStorageMapperInterface $productOfferServiceStorageMapper
     */
    public function __construct(
        ProductOfferServicePointStorageToStoreClientInterface $storeClient,
        ProductOfferServicePointStorageToStorageClientInterface $storageClient,
        ProductOfferServiceStorageKeyGeneratorInterface $productOfferServiceStorageKeyGenerator,
        ProductOfferServicePointStorageToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferServiceStorageMapperInterface $productOfferServiceStorageMapper
    ) {
        $this->storeClient = $storeClient;
        $this->storageClient = $storageClient;
        $this->productOfferServiceStorageKeyGenerator = $productOfferServiceStorageKeyGenerator;
        $this->utilEncodingService = $utilEncodingService;
        $this->productOfferServiceStorageMapper = $productOfferServiceStorageMapper;
    }

    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer
     */
    public function getProductOfferServiceStorageCollectionByProductOfferReferences(
        array $productOfferReferences
    ): ProductOfferServiceStorageCollectionTransfer {
        $productOfferServiceStorageCollectionTransfer = new ProductOfferServiceStorageCollectionTransfer();

        $storageKeys = $this->productOfferServiceStorageKeyGenerator->generateKeys(
            $productOfferReferences,
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        if (!$storageKeys) {
            return $productOfferServiceStorageCollectionTransfer;
        }

        $productOfferServiceStorageData = array_filter($this->storageClient->getMulti($storageKeys));
        if (!$productOfferServiceStorageData) {
            return $productOfferServiceStorageCollectionTransfer;
        }

        foreach ($productOfferServiceStorageData as $productOfferServiceStorageItem) {
            $decodedProductOfferServiceStorageItem = $this->utilEncodingService->decodeJson($productOfferServiceStorageItem, true);
            if (!$decodedProductOfferServiceStorageItem) {
                continue;
            }

            $productOfferServiceStorageTransfer = $this->productOfferServiceStorageMapper->mapProductOfferServiceStorageDataToProductOfferServiceStorageTransfer(
                $decodedProductOfferServiceStorageItem,
                new ProductOfferServiceStorageTransfer(),
            );

            $productOfferServiceStorageCollectionTransfer->addProductOfferService($productOfferServiceStorageTransfer);
        }

        return $productOfferServiceStorageCollectionTransfer;
    }
}
