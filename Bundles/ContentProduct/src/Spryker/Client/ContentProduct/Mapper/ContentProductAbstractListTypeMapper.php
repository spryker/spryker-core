<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Mapper;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTermException;

class ContentProductAbstractListTypeMapper implements ContentProductAbstractListTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface[]
     */
    protected $contentProductTermExecutors;

    /**
     * @param \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface $contentStorageClient
     * @param array $contentProductTermExecutors
     */
    public function __construct(ContentProductToContentStorageClientInterface $contentStorageClient, array $contentProductTermExecutors)
    {
        $this->contentStorageClient = $contentStorageClient;
        $this->contentProductTermExecutors = $contentProductTermExecutors;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTermException
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer
    {
        $contentTypeContextTransfer = $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);

        if (!$contentTypeContextTransfer) {
            return null;
        }

        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentProductTermExecutors[$term])) {
            throw new InvalidProductAbstractListTermException(
                sprintf('There is no matching Term for ProductAbstractListType when provided with term %s.', $term)
            );
        }

        $productAbstractListTermToProductAbstractListTypeExecutor = $this->contentProductTermExecutors[$term];

        return $productAbstractListTermToProductAbstractListTypeExecutor->execute($contentTypeContextTransfer);
    }
}
