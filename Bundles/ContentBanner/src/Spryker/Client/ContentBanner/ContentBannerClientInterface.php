<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;

interface ContentBannerClientInterface
{
    /**
     * Specification:
     * - Fetches Banner by ID.
     * - Executes the term for the banner, resulting in the banner.
     *
     * @api
     *
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function executeBannerTypeByKey(string $contentKey, string $localeName): ?ContentBannerTypeTransfer;

    /**
     * Specification:
     * - Fetches Banner by IDs.
     * - Executes the term for the banner, resulting in the banner.
     *
     * @api
     *
     * @param array $contentKeys
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer[]
     */
    public function executeBannerTypeByKeys(array $contentKeys, string $localeName): array;
}
