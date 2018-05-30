<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage\Storage;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientInterface;
use Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceInterface;
use Spryker\Shared\NavigationStorage\NavigationStorageConstants;

class NavigationStorage implements NavigationStorageInterface
{
    /**
     * @var \Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @param \Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(NavigationStorageToStorageClientInterface $storageClient, NavigationStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    public function findNavigationTreeByNavigationKey($navigationKey, $localeName)
    {
        $storageKey = $this->generateKey($navigationKey, $localeName);
        $navigationTreeData = $this->storageClient->get($storageKey);

        if (!$navigationTreeData) {
            return new NavigationStorageTransfer();
        }

        return $this->mapNavigationTree($navigationTreeData);
    }

    /**
     * @param array $navigationTreeData
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    protected function mapNavigationTree(array $navigationTreeData)
    {
        $navigationTreeTransfer = new NavigationStorageTransfer();
        $navigationTreeTransfer->fromArray($navigationTreeData, true);

        return $navigationTreeTransfer;
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKey($keyName, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setLocale($localeName);

        return $this->synchronizationService->getStorageKeyBuilder(NavigationStorageConstants::RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
