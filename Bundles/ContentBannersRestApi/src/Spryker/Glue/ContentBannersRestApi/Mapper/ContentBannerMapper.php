<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Mapper;

use Generated\Shared\Transfer\RestContentBannerAttributesTransfer;

class ContentBannerMapper implements ContentBannerMapperInterface
{
    /**
     * @param array $content
     *
     * @return \Generated\Shared\Transfer\RestContentBannerAttributesTransfer
     */
    public function mapContentTransferToRestContentBannerAttributes(array $content): RestContentBannerAttributesTransfer
    {
        return (new RestContentBannerAttributesTransfer())->fromArray($content);
    }
}
