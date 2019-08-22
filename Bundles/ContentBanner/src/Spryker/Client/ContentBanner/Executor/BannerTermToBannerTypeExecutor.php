<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Executor;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;

class BannerTermToBannerTypeExecutor implements ContentBannerTermExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentBannerTypeTransfer
    {
        $bannerTermTransfer = $this->mapContentTypeParametersToTransfer($contentTypeContextTransfer);

        return (new ContentBannerTypeTransfer())->fromArray($bannerTermTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentBannerTermTransfer
     */
    protected function mapContentTypeParametersToTransfer(ContentTypeContextTransfer $contentTypeContextTransfer): ContentBannerTermTransfer
    {
        return (new ContentBannerTermTransfer())->fromArray($contentTypeContextTransfer->getParameters(), true);
    }
}
