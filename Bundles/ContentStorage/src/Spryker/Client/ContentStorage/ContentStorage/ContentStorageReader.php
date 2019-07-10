<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\ContentStorage;

use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface;
use Spryker\Shared\ContentStorage\ContentStorageConfig;

class ContentStorageReader implements ContentStorageReaderInterface
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
     * @param \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ContentStorageToStorageClientInterface $storageClient,
        ContentStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContextByKey(string $contentKey, string $localeName): ?ContentTypeContextTransfer
    {
        $storageKey = $this->generateKey($contentKey, $localeName);
        $content = $this->storageClient->get($storageKey);

        if (!$content) {
            return null;
        }

        return (new ContentTypeContextTransfer())
            ->setIdContent($content[ContentStorageConfig::ID_CONTENT])
            ->setKey($contentKey)
            ->setTerm($content[ContentStorageConfig::TERM_KEY])
            ->setParameters($content[ContentStorageConfig::CONTENT_KEY]);
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKey(string $keyName, string $localeName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setLocale($localeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ContentStorageConfig::CONTENT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
