<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\TermQuery;

use Generated\Shared\Transfer\BannerTermTransfer;
use Generated\Shared\Transfer\BannerTypeTransfer;

class BannerTermQuery implements BannerTermQueryInterface
{
    /**
     * @param \Generated\Shared\Transfer\BannerTerm $bannerTerm
     *
     * @return \Generated\Shared\Transfer\BannerTypeTransfer
     */
    public function execute(BannerTermTransfer $bannerTerm): BannerTypeTransfer
    {
        return (new BannerTypeTransfer())
            ->setAltText($bannerTerm->getAltText())
            ->setClickUrl($bannerTerm->getClickUrl())
            ->setImageUrl($bannerTerm->getImageUrl())
            ->setSubtitle($bannerTerm->getSubtitle())
            ->setTitle($bannerTerm->getTitle());
    }
}
