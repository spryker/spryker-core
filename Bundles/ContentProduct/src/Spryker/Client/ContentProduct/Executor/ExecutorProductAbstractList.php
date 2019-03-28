<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ExecutedProductAbstractListTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\ContentProduct\ContentProductConfig;

class ExecutorProductAbstractList implements ExecutorProductAbstractListInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ExecutedProductAbstractListTransfer|null
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ?ExecutedProductAbstractListTransfer
    {
        if ($contentTypeContextTransfer->getTerm() !== ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST) {
            throw new InvalidProductAbstractListTypeException();
        }

        $executedProductAbstractListTransfer = new ExecutedProductAbstractListTransfer();
        $executedProductAbstractListTransfer->fromArray($contentTypeContextTransfer->toArray());
        $executedProductAbstractListTransfer->setContentProductAbstractList(
            $this->getProductAbstractListTransfer($contentTypeContextTransfer)
        );

        return $executedProductAbstractListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTransfer
     */
    protected function getProductAbstractListTransfer(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductAbstractListTransfer
    {
        $contentProductAbstractListTransfer = new ContentProductAbstractListTransfer();
        $contentProductAbstractListTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentProductAbstractListTransfer;
    }
}
