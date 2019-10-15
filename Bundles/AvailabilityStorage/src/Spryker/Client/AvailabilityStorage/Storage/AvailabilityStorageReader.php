<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageConfig;
use Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface;
use Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Client\Kernel\Locator;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\Kernel\Store;

class AvailabilityStorageReader implements AvailabilityStorageReaderInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(AvailabilityStorageToStorageClientInterface $storageClient, AvailabilityStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getAvailabilityAbstractAsStorageTransfer($idProductAbstract)
    {
        if (AvailabilityStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getAvailabilityFromCollectorData($idProductAbstract);
        }

        $spyAvailabilityAbstractTransfer = $this->getAvailabilityAbstract($idProductAbstract);
        $storageAvailabilityTransfer = new StorageAvailabilityTransfer();

        $isAbstractProductAvailable = $spyAvailabilityAbstractTransfer->getQuantity() !== null &&
            $spyAvailabilityAbstractTransfer->getQuantity()->greaterThan(0);
        $storageAvailabilityTransfer->setIsAbstractProductAvailable($isAbstractProductAvailable);

        $concreteAvailabilities = [];
        foreach ($spyAvailabilityAbstractTransfer->getSpyAvailabilities() as $spyAvailability) {
            $isProductConcreteAvailable = $spyAvailability->getQuantity()->greaterThan(0) || $spyAvailability->getIsNeverOutOfStock();
            $concreteAvailabilities[$spyAvailability->getSku()] = $isProductConcreteAvailable;
            if ($isProductConcreteAvailable === true && $isAbstractProductAvailable === false) {
                $storageAvailabilityTransfer->setIsAbstractProductAvailable(true);
            }
        }

        $storageAvailabilityTransfer->setConcreteProductAvailableItems($concreteAvailabilities);

        return $storageAvailabilityTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract($idProductAbstract)
    {
        $key = $this->generateKey($idProductAbstract);
        $availability = $this->storageClient->get($key);

        $spyAvailabilityAbstractEntityTransfer = new SpyAvailabilityAbstractEntityTransfer();
        if (!$availability) {
            return $spyAvailabilityAbstractEntityTransfer;
        }

        return $spyAvailabilityAbstractEntityTransfer->fromArray($availability, true);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    protected function getAvailabilityFromCollectorData($idProductAbstract): StorageAvailabilityTransfer
    {
        $clientLocatorClassName = Locator::class;
        /** @var \Spryker\Client\Availability\AvailabilityClientInterface $availabilityClient */
        $availabilityClient = $clientLocatorClassName::getInstance()->availability()->client();
        $availabilityData = $availabilityClient->findProductAvailabilityByIdProductAbstract($idProductAbstract);

        $storageAvailabilityTransfer = new StorageAvailabilityTransfer();
        if ($availabilityData === null) {
            return $storageAvailabilityTransfer;
        }

        $storageAvailabilityTransfer
            ->setIsAbstractProductAvailable($availabilityData['isAbstractProductAvailable'])
            ->setConcreteProductAvailableItems($availabilityData['concreteProductAvailableItems']);

        return $storageAvailabilityTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateKey($idProductAbstract)
    {
        $store = Store::getInstance()->getStoreName();

        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($store);
        $synchronizationDataTransfer->setReference((string)$idProductAbstract);

        return $this->synchronizationService->getStorageKeyBuilder(AvailabilityStorageConstants::AVAILABILITY_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
