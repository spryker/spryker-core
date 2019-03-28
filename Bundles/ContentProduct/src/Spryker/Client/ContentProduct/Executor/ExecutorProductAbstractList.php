<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentQueryTransfer;
use Generated\Shared\Transfer\ExecutedProductAbstractListTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\ContentProduct\ContentProductConfig;

class ExecutorProductAbstractList implements ExecutorProductAbstractListInterface
{
    /**
     * @param \Spryker\Client\ContentProduct\Executor\ContentQueryTransfer $contentQueryTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return ExecutedProductAbstractListTransfer\|null
     */
    public function execute(ContentQueryTransfer $contentQueryTransfer): ?ExecutedProductAbstractListTransfer
    {
        if ($contentQueryTransfer->getTerm() !== ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST) {
            throw new InvalidProductAbstractListTypeException();
        }

        $executedProductAbstractListTransfer = new ExecutedProductAbstractListTransfer();
        $executedProductAbstractListTransfer->fromArray($contentQueryTransfer->toArray());
        $executedProductAbstractListTransfer->setContentProductAbstractList(
            $this->getProductAbstractListTransfer($contentQueryTransfer)
        );

        return $executedProductAbstractListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentQueryTransfer $contentQueryTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTransfer
     */
    protected function getProductAbstractListTransfer(ContentQueryTransfer $contentQueryTransfer): ContentProductAbstractListTransfer
    {
        $contentProductAbstractListTransfer = new ContentProductAbstractListTransfer();
        $contentProductAbstractListTransfer->fromArray($contentQueryTransfer->getQueryParameters());

        return $contentProductAbstractListTransfer;
    }
}
