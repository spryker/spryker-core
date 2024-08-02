<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\Storage;

use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToStoreClientInterface;
use Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class AvailabilityStorage implements AvailabilityStorageInterface
{
    /**
     * @var \Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Spryker\Client\Availability\Dependency\Client\AvailabilityToStoreClientInterface
     */
    protected AvailabilityToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     * @param \Spryker\Client\Availability\Dependency\Client\AvailabilityToStoreClientInterface $storeClient
     */
    public function __construct(
        AvailabilityToStorageInterface $storage,
        KeyBuilderInterface $keyBuilder,
        $localeName,
        AvailabilityToStoreClientInterface $storeClient
    ) {
        $this->storageClient = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $localeName;
        $this->storeClient = $storeClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailability($idProductAbstract)
    {
        $storeName = $this->storeClient->getCurrentStore()->getNameOrFail();

        $key = $this->keyBuilder->generateKey($idProductAbstract, $this->locale, $storeName);
        $availability = $this->storageClient->get($key);
        if ($availability === null) {
            throw new ProductAvailabilityNotFoundException(
                sprintf('Product availability not found for "%d" product abstract id', $idProductAbstract),
            );
        }

        return $this->getMappedStorageAvailabilityTransferFromStorage($availability);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer|null
     */
    public function findProductAvailability($idProductAbstract)
    {
        $availability = $this->getProductAvailabilityFromStorage($idProductAbstract);
        if ($availability === null) {
            return null;
        }

        return $this->getMappedStorageAvailabilityTransferFromStorage($availability);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function getProductAvailabilityFromStorage($idProductAbstract)
    {
        $storeName = $this->storeClient->getCurrentStore()->getNameOrFail();

        $key = $this->keyBuilder->generateKey($idProductAbstract, $this->locale, $storeName);
        $availability = $this->storageClient->get($key);

        return $availability;
    }

    /**
     * @param array $availability
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    protected function getMappedStorageAvailabilityTransferFromStorage(array $availability)
    {
        $storageAvailabilityTransfer = new StorageAvailabilityTransfer();
        $storageAvailabilityTransfer->fromArray($availability, true);

        return $storageAvailabilityTransfer;
    }
}
