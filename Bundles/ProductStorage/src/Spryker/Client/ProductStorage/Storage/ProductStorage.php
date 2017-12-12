<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductStorage implements ProductStorageInterface
{

    /**
     * @var ProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var ProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param ProductStorageToStorageClientInterface $storageClient
     * @param ProductStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductStorageToStorageClientInterface $storageClient, ProductStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return ProductAbstractStorageTransfer
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale)
    {
        $productKey = $this->generateKey($idProductAbstract, $locale);
        $productAbstractStorageTransfer = new ProductAbstractStorageTransfer();
        $productAbstract = $this->storageClient->get($productKey);

        if (!$productAbstract) {
            return $productAbstractStorageTransfer;
        }
        $productAbstractStorageTransfer->fromArray($productAbstract, true);

        return $productAbstractStorageTransfer;
    }

    /**
     * @param $keyName
     * @param $localeName
     *
     * @return string
     */
    protected function generateKey($keyName, $localeName)
    {
        $storeName = Store::getInstance()->getStoreName();

        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setStore($storeName);

        return $this->synchronizationService->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
