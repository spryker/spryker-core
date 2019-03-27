<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Dependency\Client;

use Generated\Shared\Transfer\ContentQueryTransfer;

interface ContentBannerToContentStorageClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentQueryTransfer|null
     */
    public function findContentQueryById(int $idContent, string $localeName): ?ContentQueryTransfer;
}
