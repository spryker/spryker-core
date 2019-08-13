<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlRedirectStorageTransfer;
use Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface;
use Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface;
use Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface;
use Spryker\Client\UrlStorage\UrlStorageConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

class UrlRedirectStorageReader implements UrlRedirectStorageReaderInterface
{
    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface
     */
    protected $urlRedirectStorageMapper;

    /**
     * @param \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface $storageClient
     * @param \Spryker\Client\UrlStorage\Dependency\Service\UrlStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface $urlRedirectStorageMapper
     */
    public function __construct(
        UrlStorageToStorageInterface $storageClient,
        UrlStorageToSynchronizationServiceInterface $synchronizationService,
        UrlRedirectStorageMapperInterface $urlRedirectStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->urlRedirectStorageMapper = $urlRedirectStorageMapper;
    }

    /**
     * @param int $idRedirectUrl
     *
     * @return \Generated\Shared\Transfer\UrlRedirectStorageTransfer|null
     */
    public function findUrlRedirectStorageById(int $idRedirectUrl): ?UrlRedirectStorageTransfer
    {
        $data = $this->storageClient->get($this->generateKey($idRedirectUrl));

        if (!$data) {
            return null;
        }

        return $this->urlRedirectStorageMapper->mapStorageDataToUrlRedirectStorageTransfer($data, new UrlRedirectStorageTransfer());
    }

    /**
     * @param int $idRedirectUrl
     *
     * @return string
     */
    protected function generateKey(int $idRedirectUrl): string
    {
        if (UrlStorageConfig::isCollectorCompatibilityMode()) {
            $collectorDataKey = sprintf(
                '%s.%s.resource.redirect.%s',
                strtolower(Store::getInstance()->getStoreName()),
                strtolower(Store::getInstance()->getCurrentLocale()),
                $idRedirectUrl
            );

            return $collectorDataKey;
        }

        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($idRedirectUrl);

        return $this->synchronizationService
            ->getStorageKeyBuilder(UrlStorageConstants::REDIRECT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
