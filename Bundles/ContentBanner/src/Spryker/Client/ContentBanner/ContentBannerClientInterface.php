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
     * - Executes the ContentBannerTerm.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return array
     */
    public function execute(ContentBannerTransfer $contentBannerTransfer): array;
}
