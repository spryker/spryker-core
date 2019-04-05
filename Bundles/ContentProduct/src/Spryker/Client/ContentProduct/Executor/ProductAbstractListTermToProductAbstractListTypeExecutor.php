<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\ContentProduct\ContentProductConfig;

class ProductAbstractListTermToProductAbstractListTypeExecutor implements ContentProductTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductAbstractListTypeTransfer
    {
        $contentProductAbstractListTermTransfer = $this->mapContentTypeContextTransferToContentProductAbstractListTermTransfer(
            $contentTypeContextTransfer
        );

        $contentProductAbstractListTypeTransfer = new ContentProductAbstractListTypeTransfer();
        $contentProductAbstractListTypeTransfer->setIdProductAbstracts($contentProductAbstractListTermTransfer->getIdProductAbstracts());

        return $contentProductAbstractListTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer
     */
    protected function mapContentTypeContextTransferToContentProductAbstractListTermTransfer(
        ContentTypeContextTransfer $contentTypeContextTransfer
    ): ContentProductAbstractListTermTransfer {
        if ($contentTypeContextTransfer->getTerm() !== ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST) {
            throw new InvalidProductAbstractListTypeException(
                sprintf('There is no ContentProductAbstractList Term which can work with the term %s.', $contentTypeContextTransfer->getTerm())
            );
        }

        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();
        $contentProductAbstractListTermTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentProductAbstractListTermTransfer;
    }
}
