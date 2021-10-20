<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageConfig;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface;
use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;

class UrlStorageCategoryNodeMapper implements UrlStorageCategoryNodeMapperInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface $localeClient
     */
    public function __construct(
        CategoryStorageToSynchronizationServiceInterface $synchronizationService,
        CategoryStorageToStoreClientInterface $storeClient,
        CategoryStorageToLocaleClientInterface $localeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function mapUrlStorageTransferToUrlStorageResourceMapTransfer(
        UrlStorageTransfer $urlStorageTransfer,
        array $options = []
    ): UrlStorageResourceMapTransfer {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idCategoryNode = $urlStorageTransfer->getFkResourceCategorynode();

        if ($idCategoryNode === null) {
            return $urlStorageResourceMapTransfer;
        }

        $resourceKey = $this->generateKey(
            $idCategoryNode,
            $this->storeClient->getCurrentStore()->getNameOrFail(),
            $options['locale'] ?? $this->localeClient->getCurrentLocale(),
        );

        return $urlStorageResourceMapTransfer
            ->setResourceKey($resourceKey)
            ->setType(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME);
    }

    /**
     * @param int $idCategoryNode
     * @param string $storeName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKey(int $idCategoryNode, string $storeName, string $localeName): string
    {
        if (CategoryStorageConfig::isCollectorCompatibilityMode()) {
            return sprintf(
                '%s.%s.resource.categorynode.%s',
                strtolower($storeName),
                strtolower($localeName),
                $idCategoryNode,
            );
        }
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setLocale($localeName)
            ->setReference($idCategoryNode);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
