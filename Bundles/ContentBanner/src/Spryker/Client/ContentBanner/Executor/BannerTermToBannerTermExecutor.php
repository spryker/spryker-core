<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Executor;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Shared\ContentBanner\ContentBannerConfig;

class BannerTermToBannerTermExecutor implements BannerTermExecutorInterface
{
    /**
     * @return string
     */
    public static function getTerm(): string
    {
        return ContentBannerConfig::CONTENT_TERM_BANNER;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $contentTypeContextTransfer
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer
     */
    public function execute(ContentTypeContextTransfer $contentTypeContextTransfer): ContentBannerTypeTransfer
    {
        $bannerTermTransfer = $this->mapContentTypeParametersToTransfer($contentTypeContextTransfer);

        return (new ContentBannerTypeTransfer())
            ->setAltText($bannerTermTransfer->getAltText())
            ->setClickUrl($bannerTermTransfer->getClickUrl())
            ->setImageUrl($bannerTermTransfer->getImageUrl())
            ->setSubtitle($bannerTermTransfer->getSubtitle())
            ->setTitle($bannerTermTransfer->getTitle());
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
