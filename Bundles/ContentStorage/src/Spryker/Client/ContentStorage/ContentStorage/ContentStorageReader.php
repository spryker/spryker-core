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
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingServiceInterface;
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
     * @var \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ContentStorageToStorageClientInterface $storageClient,
        ContentStorageToSynchronizationServiceInterface $synchronizationService,
        ContentStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
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

        return $this->setContentTypeContextData($content, $contentKey);
    }

    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\ContentTypeContextTransfer>
     *
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer[]
     */
    public function getContentTypeContextByKeys(array $contentKeys, string $localeName): array
    {
        $contentStorageData = $this->storageClient->getMulti(
            $this->generateKeys($contentKeys, $localeName)
        );

        if (!$contentStorageData) {
            return [];
        }

        $contentStorageTransfers = [];
        foreach ($contentStorageData as $contentStorageKey => $contentStorageDatum) {
            if (!$contentStorageDatum) {
                continue;
            }

            $decodedContentsStorageData = $this->utilEncodingService->decodeJson($contentStorageDatum, true);
            if (!$decodedContentsStorageData) {
                continue;
            }

            $contentKey = $this->getContentKey($contentStorageKey);

            $contentStorageTransfers[$contentKey] = $this->setContentTypeContextData($decodedContentsStorageData, $contentKey);
        }

        return $contentStorageTransfers;
    }

    /**
     * @param string[] $contentKeys
     * @param string $localeName
     *
     * @return string[]
     */
    protected function generateKeys(array $contentKeys, string $localeName): array
    {
        $contentStorageKeys = [];
        foreach ($contentKeys as $contentKey) {
            $contentStorageKeys[] = $this->generateKey($contentKey, $localeName);
        }

        return $contentStorageKeys;
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

    /**
     * @param string $contentStorageKey
     *
     * @return string
     */
    protected function getContentKey(string $contentStorageKey): string
    {
        $storageKeyArray = explode(':', $contentStorageKey);

        return end($storageKeyArray);
    }

    /**
     * @phpstan-param array<string, mixed> $content
     *
     * @param array $content
     * @param string $contentKey
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer
     */
    protected function setContentTypeContextData(array $content, string $contentKey): ContentTypeContextTransfer
    {
        return (new ContentTypeContextTransfer())
            ->setIdContent($content[ContentStorageConfig::ID_CONTENT])
            ->setKey($contentKey)
            ->setTerm($content[ContentStorageConfig::TERM_KEY])
            ->setParameters($content[ContentStorageConfig::CONTENT_KEY]);
    }
}
