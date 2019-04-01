<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Generated\Shared\Transfer\ContentBannerTermTransfer;

interface ContentBannerClientInterface
{
    /**
     * Specification:
     * - Executes the ContentBannerTerm.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBannerTransfer
     *
     * @return array
     */
    public function execute(ContentBannerTermTransfer $contentBannerTransfer): array;
}
