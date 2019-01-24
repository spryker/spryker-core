<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Mapper;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\RestContentBannerAttributesTransfer;

interface ContentBannerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\RestContentBannerAttributesTransfer
     */
    public function mapContentTransferToRestContentBannerAttributes(ContentTransfer $contentTransfer): RestContentBannerAttributesTransfer;
}
