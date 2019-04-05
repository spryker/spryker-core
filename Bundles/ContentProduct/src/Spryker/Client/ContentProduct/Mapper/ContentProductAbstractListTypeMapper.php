<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Mapper;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;

class ContentProductAbstractListTypeMapper implements ContentProductAbstractListTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface[]
     */
    protected $contentProductTermExecutors;

    /**
     * @param \Spryker\Client\ContentProduct\Executor\ContentProductTermExecutorInterface[] $contentProductTermExecutors
     */
    public function __construct(array $contentProductTermExecutors)
    {
        $this->contentProductTermExecutors = $contentProductTermExecutors;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductAbstractListTypeTransfer
    {
        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentProductTermExecutors[$term])) {
            throw new InvalidProductAbstractListTypeException(
                sprintf('There is no matching Term for ProductAbstractListType when provided with term %s.', $term)
            );
        }

        $productAbstractListTermToProductAbstractListTypeExecutor = $this->contentProductTermExecutors[$term];

        return $productAbstractListTermToProductAbstractListTypeExecutor->execute($contentTypeContextTransfer);
    }
}
