<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Executor;

use Generated\Shared\Transfer\BannerTermTransfer;
use Generated\Shared\Transfer\BannerTypeTransfer;

interface BannerTermToBannerTypeExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\BannerTerm $bannerTerm
     *
     * @return \Generated\Shared\Transfer\BannerTypeTransfer
     */
    public function execute(BannerTermTransfer $bannerTerm): BannerTypeTransfer;
}
