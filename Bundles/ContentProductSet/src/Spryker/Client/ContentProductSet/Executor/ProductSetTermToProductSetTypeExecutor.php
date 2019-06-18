<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProductSet\Executor;

use Generated\Shared\Transfer\ContentProductSetTermTransfer;
use Generated\Shared\Transfer\ContentProductSetTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

class ProductSetTermToProductSetTypeExecutor implements ContentProductSetTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentProductSetTypeTransfer
    {
        $contentProductSetTermTransfer = $this->mapContentTypeContextTransferToContentProductSetTermTransfer(
            $contentTypeContextTransfer
        );

        $contentProductSetTypeTransfer = new ContentProductSetTypeTransfer();
        $contentProductSetTypeTransfer->setIdProductSet($contentProductSetTermTransfer->getIdProductSet());

        return $contentProductSetTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTermTransfer
     */
    protected function mapContentTypeContextTransferToContentProductSetTermTransfer(
        ContentTypeContextTransfer $contentTypeContextTransfer
    ): ContentProductSetTermTransfer {
        $contentProductSetTermTransfer = new ContentProductSetTermTransfer();
        $contentProductSetTermTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentProductSetTermTransfer;
    }
}
