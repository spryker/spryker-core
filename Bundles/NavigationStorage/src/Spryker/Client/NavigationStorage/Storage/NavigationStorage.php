<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage\Storage;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientInterface;
use Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceInterface;
use Spryker\Client\NavigationStorage\NavigationStorageConfig;
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
        if (NavigationStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getNavigationTreeFromCollectorData($navigationKey, $localeName);
        }

        $storageKey = $this->generateKey($navigationKey, $localeName);
        $navigationTreeData = $this->storageClient->get($storageKey);

        if (!$navigationTreeData) {
            return new NavigationStorageTransfer();
        }

        return $this->mapNavigationTree($navigationTreeData);
    }

    /**
     * @param string $navigationKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    protected function getNavigationTreeFromCollectorData(string $navigationKey, string $localeName): NavigationStorageTransfer
    {
        $clientLocatorClass = Locator::class;
        /** @var \Spryker\Client\Navigation\NavigationClientInterface $navigationClient */
        $navigationClient = $clientLocatorClass::getInstance()->navigation()->client();
        $navigationTreeTransfer = $navigationClient->findNavigationTreeByKey($navigationKey, $localeName);

        if (!$navigationTreeTransfer) {
            return new NavigationStorageTransfer();
        }

        $nodes = [];
        foreach ($navigationTreeTransfer->getNodes() as $nodeTransfer) {
            $nodes[] = $this->formatCollectorData($nodeTransfer);
        }
        $navigationTreeData = $navigationTreeTransfer->getNavigation()->toArray();
        $navigationTreeData['nodes'] = $nodes;

        return $this->mapNavigationTree($navigationTreeData);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeNodeTransfer $navigationTreeNodeTransfer
     *
     * @return array
     */
    protected function formatCollectorData(NavigationTreeNodeTransfer $navigationTreeNodeTransfer): array
    {
        $nodeArray = $navigationTreeNodeTransfer->getNavigationNode()->toArray();
        $localizedAttributes = [];
        foreach ($navigationTreeNodeTransfer->getNavigationNode()->getNavigationNodeLocalizedAttributes() as $navigationNodeLocalizedAttributeTransfer) {
            $localizedAttributes = $navigationNodeLocalizedAttributeTransfer->toArray();
        }
        $nodeArray = array_merge($nodeArray, $localizedAttributes);

        if ($navigationTreeNodeTransfer->getChildren()->count() > 0) {
            $childNodes = [];
            foreach ($navigationTreeNodeTransfer->getChildren() as $navigationTreeNodeTransfer) {
                $childNodes[] = $this->formatCollectorData($navigationTreeNodeTransfer);
            }
            $nodeArray['children'] = $childNodes;
        }

        return $nodeArray;
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
