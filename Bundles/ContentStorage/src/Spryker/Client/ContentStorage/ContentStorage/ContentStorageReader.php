<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage\ContentStorage;

use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface;
use Spryker\Client\ContentStorage\Resolver\ContentResolverInterface;
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
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer
    {
        $storageKey = $this->generateKey((string)$idContent, $localeName);
        $content = $this->storageClient->get($storageKey);

        if (!$content) {
            return null;
        }

        $contentExtractorPlugin = $this->contentResolver->getContentPlugin($content[ContentStorageConfig::TERM_KEY]);

        return (new ExecutedContentStorageTransfer())
            ->setIdContent($idContent)
            ->setType($contentExtractorPlugin->getTypeKey())
            ->setContent($contentExtractorPlugin->execute($content[ContentStorageConfig::CONTENT_KEY]));
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContext(int $idContent, string $localeName): ?ContentTypeContextTransfer
    {
        $storageKey = $this->generateKey((string)$idContent, $localeName);
        $content = $this->storageClient->get($storageKey);

        if (!$content) {
            return null;
        }

        return (new ContentTypeContextTransfer())
            ->setIdContent($idContent)
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
