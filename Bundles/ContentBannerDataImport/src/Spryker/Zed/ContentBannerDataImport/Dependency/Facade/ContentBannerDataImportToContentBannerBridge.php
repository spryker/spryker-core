<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentBannerDataImportToContentBannerBridge implements ContentBannerDataImportToContentBannerInterface
{
    /**
     * @var \Spryker\Zed\ContentBanner\Business\ContentBannerFacadeInterface
     */
    protected $contentBannerFacade;

    /**
     * @param \Spryker\Zed\ContentBanner\Business\ContentBannerFacadeInterface $contentBannerFacade
     */
    public function __construct($contentBannerFacade)
    {
        $this->contentBannerFacade = $contentBannerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentBanner(
        ContentBannerTransfer $contentBannerTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentBannerFacade->validateContentBanner($contentBannerTransfer);
    }
}
