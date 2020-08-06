<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentNavigation\Executor;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentNavigationTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

class ContentTermToNavigationTypeExecutor implements ContentNavigationTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentNavigationTypeTransfer
    {
        $navigationTermTransfer = $this->mapContentTypeParametersToTransfer($contentTypeContextTransfer);

        return (new ContentNavigationTypeTransfer())->fromArray($navigationTermTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    protected function mapContentTypeParametersToTransfer(ContentTypeContextTransfer $contentTypeContextTransfer): ContentNavigationTermTransfer
    {
        return (new ContentNavigationTermTransfer())->fromArray($contentTypeContextTransfer->getParameters(), true);
    }
}
