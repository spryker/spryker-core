<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentNavigation\Mapper;

use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface;
use Spryker\Client\ContentNavigation\Exception\MissingNavigationTermException;

class ContentNavigationTypeMapper implements ContentNavigationTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var \Spryker\Client\ContentNavigation\Executor\ContentNavigationTermExecutorInterface[]
     */
    protected $contentNavigationTermExecutors;

    /**
     * @param \Spryker\Client\ContentNavigation\Dependency\Client\ContentNavigationToContentStorageClientInterface $contentStorageClient
     * @param \Spryker\Client\ContentNavigation\Executor\ContentNavigationTermExecutorInterface[] $contentNavigationTermExecutors
     */
    public function __construct(ContentNavigationToContentStorageClientInterface $contentStorageClient, array $contentNavigationTermExecutors)
    {
        $this->contentStorageClient = $contentStorageClient;
        $this->contentNavigationTermExecutors = $contentNavigationTermExecutors;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentNavigation\Exception\MissingNavigationTermException
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTypeTransfer|null
     */
    public function executeNavigationTypeByKey(string $contentKey, string $localeName): ?ContentNavigationTypeTransfer
    {
        $contentTypeContextTransfer = $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);

        if (!$contentTypeContextTransfer) {
            return null;
        }

        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentNavigationTermExecutors[$term])) {
            throw new MissingNavigationTermException(
                sprintf('There is no matching Term for NavigationType when provided with term %s.', $term)
            );
        }

        $navigationTermToNavigationTypeExecutor = $this->contentNavigationTermExecutors[$term];

        return $navigationTermToNavigationTypeExecutor->execute($contentTypeContextTransfer);
    }
}
