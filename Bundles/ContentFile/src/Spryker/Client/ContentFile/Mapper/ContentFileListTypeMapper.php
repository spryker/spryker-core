<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Mapper;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface;
use Spryker\Client\ContentFile\Exception\InvalidFileListTermException;

class ContentFileListTypeMapper implements ContentFileListTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Client\ContentFile\Executor\ContentFileListTermExecutorInterface[]
     */
    protected $contentFileListTermExecutors;

    /**
     * @param \Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface $contentStorageClient
     * @param \Spryker\Client\ContentFile\Executor\ContentFileListTermExecutorInterface[] $contentFileListTermExecutors
     */
    public function __construct(ContentFileToContentStorageClientInterface $contentStorageClient, array $contentFileListTermExecutors)
    {
        $this->contentStorageClient = $contentStorageClient;
        $this->contentFileListTermExecutors = $contentFileListTermExecutors;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentFile\Exception\InvalidFileListTermException
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeFileListTypeByKey(string $contentKey, string $localeName): ?ContentFileListTypeTransfer
    {
        $contentTypeContextTransfer = $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);

        if (!$contentTypeContextTransfer) {
            return null;
        }

        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentFileListTermExecutors[$term])) {
            throw new InvalidFileListTermException(
                sprintf('There is no matching Term for FileListType when provided with term %s.', $term)
            );
        }

        $fileListTermToFileListTypeExecutor = $this->contentFileListTermExecutors[$term];

        return $fileListTermToFileListTypeExecutor->execute($contentTypeContextTransfer);
    }
}
