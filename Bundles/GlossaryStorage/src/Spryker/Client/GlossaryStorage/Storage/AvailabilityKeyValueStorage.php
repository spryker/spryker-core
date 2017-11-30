<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Storage;

use Generated\Shared\Transfer\SpyAvailabilityAbstractTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageInterface;
use Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;
use Spryker\Shared\Kernel\Store;

class AvailabilityKeyValueStorage implements AvailabilityKeyValueStorageInterface
{

    const PREFIX_PS = 'ps:';

    /**
     * @var \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\GlossaryStorage\Dependency\Client\GlossaryStorageToStorageInterface $storageClient
     * @param \Spryker\Client\GlossaryStorage\Dependency\Service\GlossaryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(GlossaryStorageToStorageInterface $storageClient, GlossaryStorageToSynchronizationServiceInterface $synchronizationService)
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
        $spyAvailabilityAbstractTransfer = $this->getAvailabilityAbstract($idProductAbstract);
        $storageAvailabilityTransfer = new StorageAvailabilityTransfer();

        $isAbstractProductAvailable = $spyAvailabilityAbstractTransfer->getQuantity() > 0;
        $storageAvailabilityTransfer->setIsAbstractProductAvailable($isAbstractProductAvailable);

        $concreteAvailabilities = [];
        foreach ($spyAvailabilityAbstractTransfer->getSpyAvailabilities() as $spyAvailability) {
            $concreteAvailabilities[$spyAvailability->getSku()] = $spyAvailability->getQuantity() > 0 || $spyAvailability->getIsNeverOutOfStock();
            ;
        }

        $storageAvailabilityTransfer->setConcreteProductAvailableItems($concreteAvailabilities);

        return $storageAvailabilityTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractTransfer
     */
    public function getAvailabilityAbstract($idProductAbstract)
    {
        $key = $this->generateKey($idProductAbstract);
        $availability = $this->storageClient->get($key, self::PREFIX_PS);

        $spyAvailabilityAbstractTransfer = new SpyAvailabilityAbstractTransfer();
        if ($availability === null) {
            return $spyAvailabilityAbstractTransfer;
        }

        return $spyAvailabilityAbstractTransfer->fromArray($availability, true);
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
        $synchronizationDataTransfer->setReference($idProductAbstract);

        return $this->synchronizationService->getStorageKeyBuilder(GlossaryStorageConstants::AVAILABILITY_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

}
