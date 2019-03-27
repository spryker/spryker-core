<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Mapper;

use Generated\Shared\Transfer\BannerTypeTransfer;
use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Generated\Shared\Transfer\RestContentBannerAttributesTransfer;

interface ContentBannerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExecutedContentStorageTransfer $executedContentStorageTransfer
     * @param \Generated\Shared\Transfer\RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentBannerAttributesTransfer
     */
    public function mapExecutedContentStorageTransferToRestContentBannerAttributes(
        ExecutedContentStorageTransfer $executedContentStorageTransfer,
        RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
    ): RestContentBannerAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\BannerTypeTransfer $bannerTypeTransfer
     * @param \Generated\Shared\Transfer\RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentBannerAttributesTransfer
     */
    public function mapBannerTypeTransferToRestContentBannerAttributes(
        BannerTypeTransfer $bannerTypeTransfer,
        RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
    ): RestContentBannerAttributesTransfer;
}
