<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Executor;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

class ContentFileListTermToFileListListTypeExecutor implements ContentFileListTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentFileListTypeTransfer
    {
        $contentFileListTermTransfer = $this->mapContentTypeContextTransferToContentFileListTermTransfer(
            $contentTypeContextTransfer
        );

        $contentFileListTypeTransfer = new ContentFileListTypeTransfer();
        $contentFileListTypeTransfer->setFileIds($contentFileListTermTransfer->getFileIds());

        return $contentFileListTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentFileListTermTransfer
     */
    protected function mapContentTypeContextTransferToContentFileListTermTransfer(
        ContentTypeContextTransfer $contentTypeContextTransfer
    ): ContentFileListTermTransfer {
        $contentFileListTermTransfer = new ContentFileListTermTransfer();
        $contentFileListTermTransfer->fromArray($contentTypeContextTransfer->getParameters());

        return $contentFileListTermTransfer;
    }
}
