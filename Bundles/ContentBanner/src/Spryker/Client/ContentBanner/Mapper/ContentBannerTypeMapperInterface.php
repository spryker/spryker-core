<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Mapper;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;

interface ContentBannerTypeMapperInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function findBannerTypeById(int $idContent, string $localeName): ?ContentBannerTypeTransfer;
}
