<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Mapper;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Generated\Shared\Transfer\RestContentBannerAttributesTransfer;

interface ContentBannerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer $bannerTypeTransfer
     * @param \Generated\Shared\Transfer\RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentBannerAttributesTransfer
     */
    public function mapBannerTypeTransferToRestContentBannerAttributes(
        ContentBannerTypeTransfer $bannerTypeTransfer,
        RestContentBannerAttributesTransfer $restContentBannerAttributesTransfer
    ): RestContentBannerAttributesTransfer;
}
