<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface;
use Spryker\Client\ContentStorage\Resolver\ContentResolverInterface;
use Spryker\Shared\ContentStorage\ContentStorageConstants;

class ContentStorage implements ContentStorageInterface
{
    /**
     * @var \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ContentStorage\Resolver\ContentResolverInterface
     */
    protected $contentResolver;

    /**
     * @param \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ContentStorage\Resolver\ContentResolverInterface $contentResolver
     */
    public function __construct(
        ContentStorageToStorageClientInterface $storageClient,
        ContentStorageToSynchronizationServiceInterface $synchronizationService,
        ContentResolverInterface $contentResolver
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->contentResolver = $contentResolver;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function findContentById(int $idContent, string $localeName)
    {
        $storageKey = $this->generateKey((string)$idContent, $localeName);
        $contentItem = $this->storageClient->get($storageKey);

        if (empty($contentItem)) {
            return null;
        }

        $contentItemExtractorPlugin = $this->contentResolver->getContentItemPlugin($contentItem[ContentStorageConstants::TERM_KEY]);
        $oarameters = $contentItem[ContentStorageConstants::CONTENT_KEY];

        return $contentItemExtractorPlugin->execute($oarameters);
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

        return $this->synchronizationService->getStorageKeyBuilder(ContentStorageConstants::CONTENT_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
