<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyAvailabilityEntityTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageConfig;
use Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface;
use Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface;
use Spryker\Client\Kernel\Locator;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
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
     * @var \Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface
     */
    protected $availabilityStorageMapper;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @var string|null
     */
    protected static $storeName;

    /**
     * @param \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface $availabilityStorageMapper
     */
    public function __construct(
        AvailabilityStorageToStorageClientInterface $storageClient,
        AvailabilityStorageToSynchronizationServiceInterface $synchronizationService,
        AvailabilityStorageMapperInterface $availabilityStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->availabilityStorageMapper = $availabilityStorageMapper;
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
        $isProductAbstractAvailable = $this->isProductAbstractAvailable($spyAvailabilityAbstractTransfer);
        $storageAvailabilityTransfer->setIsAbstractProductAvailable($isProductAbstractAvailable);

        $concreteAvailabilities = [];
        foreach ($spyAvailabilityAbstractTransfer->getSpyAvailabilities() as $spyAvailability) {
            $isProductConcreteAvailable = $this->isProductConcreteAvailable($spyAvailability);
            $concreteAvailabilities[$spyAvailability->getSku()] = $isProductConcreteAvailable;
            if ($isProductConcreteAvailable === true && $isProductAbstractAvailable === false) {
                $storageAvailabilityTransfer->setIsAbstractProductAvailable(true);
            }
        }

        $storageAvailabilityTransfer->setConcreteProductAvailableItems($concreteAvailabilities);

        return $storageAvailabilityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer $spyAvailabilityAbstractTransfer
     *
     * @return bool
     */
    protected function isProductAbstractAvailable(SpyAvailabilityAbstractEntityTransfer $spyAvailabilityAbstractTransfer): bool
    {
        return $spyAvailabilityAbstractTransfer->getQuantity() !== null &&
            $spyAvailabilityAbstractTransfer->getQuantity()->greaterThan(0);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAvailabilityEntityTransfer $spyAvailability
     *
     * @return bool
     */
    protected function isProductConcreteAvailable(SpyAvailabilityEntityTransfer $spyAvailability): bool
    {
        return $spyAvailability->getIsNeverOutOfStock() ||
            ($spyAvailability->getQuantity() !== null && $spyAvailability->getQuantity()->greaterThan(0));
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findAbstractProductAvailability(int $idProductAbstract): ?ProductAbstractAvailabilityTransfer
    {
        $availabilityStorageData = $this->storageClient->get(
            $this->generateKey($idProductAbstract)
        );

        if (!$availabilityStorageData) {
            return null;
        }

        return $this->availabilityStorageMapper
            ->mapAvailabilityStorageDataToProductAbstractAvailabilityTransfer(
                $availabilityStorageData,
                new ProductAbstractAvailabilityTransfer()
            );
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
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->getStoreName());
        $synchronizationDataTransfer->setReference((string)$idProductAbstract);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        if (static::$storeName === null) {
            static::$storeName = Store::getInstance()->getStoreName();
        }

        return static::$storeName;
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(AvailabilityStorageConstants::AVAILABILITY_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
