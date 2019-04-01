<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Dependency\Client;

use Generated\Shared\Transfer\BannerTypeTransfer;

interface ContentBannersRestApiToContentBannerClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\BannerTypeTransfer|null
     */
    public function findBannerById(int $idContent, string $localeName): ?BannerTypeTransfer;
}
