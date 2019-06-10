<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet\Mapper;

use Generated\Shared\Transfer\ContentProductSetTypeTransfer;
use Spryker\Client\ContentProductSet\Dependency\Client\ContentProductSetToContentStorageClientInterface;
use Spryker\Client\ContentProductSet\Exception\InvalidProductSetTermException;

class ContentProductSetTypeMapper implements ContentProductSetTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentProductSet\Dependency\Client\ContentProductSetToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Client\ContentProductSet\Executor\ContentProductSetTermExecutorInterface[]
     */
    protected $contentProductSetTermExecutors;

    /**
     * @param \Spryker\Client\ContentProductSet\Dependency\Client\ContentProductSetToContentStorageClientInterface $contentStorageClient
     * @param \Spryker\Client\ContentProductSet\Executor\ContentProductSetTermExecutorInterface[] $contentProductSetTermExecutors
     */
    public function __construct(ContentProductSetToContentStorageClientInterface $contentStorageClient, array $contentProductSetTermExecutors)
    {
        $this->contentStorageClient = $contentStorageClient;
        $this->contentProductSetTermExecutors = $contentProductSetTermExecutors;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentProductSet\Exception\InvalidProductSetTermException
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null
     */
    public function executeProductSetTypeByKey(string $contentKey, string $localeName): ?ContentProductSetTypeTransfer
    {
        $contentTypeContextTransfer = $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);

        if (!$contentTypeContextTransfer) {
            return null;
        }

        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentProductSetTermExecutors[$term])) {
            throw new InvalidProductSetTermException(
                sprintf('There is no matching Term for ProductSetType when provided with term %s.', $term)
            );
        }

        $productSetTermToProductSetTypeExecutor = $this->contentProductSetTermExecutors[$term];

        return $productSetTermToProductSetTypeExecutor->execute($contentTypeContextTransfer);
    }
}
