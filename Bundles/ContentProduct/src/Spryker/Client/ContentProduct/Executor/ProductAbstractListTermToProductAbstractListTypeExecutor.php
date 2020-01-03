<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

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
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer
     */
    protected function mapContentTypeContextTransferToContentProductAbstractListTermTransfer(
        ContentTypeContextTransfer $contentTypeContextTransfer
    ): ContentProductAbstractListTermTransfer {
        $contentProductAbstractListTermTransfer = new ContentProductAbstractListTermTransfer();
        $contentProductAbstractListTermTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentProductAbstractListTermTransfer;
    }
}
