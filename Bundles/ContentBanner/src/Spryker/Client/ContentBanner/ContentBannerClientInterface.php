<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Generated\Shared\Transfer\ContentBannerTypeTransfer;

interface ContentBannerClientInterface
{
    /**
     * Specification:
     * - Executes the ContentBannerTerm.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return array
     */
    public function execute(ContentBannerTransfer $contentBannerTransfer): array;

    /**
     * Specification:
     * - Fetches Banner by ID.
     * - Executes the term for the banner, resulting in the banner.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function findBannerById(int $idContent, string $localeName): ?ContentBannerTypeTransfer;
}
