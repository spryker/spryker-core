<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTransfer;

interface ContentBannerClientInterface
{
    /**
     * Specification:
     * - Executes the ContentBanner term.
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
     * - Fetches Unexecuted Banner by ID.
     * - Executes the term for the banner, resulting in the banner.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ExecutedBannerTransfer
     */
    public function getExecutedBannerById(int $idContent, string $localeName): ?ExecutedBannerTransfer;
}
