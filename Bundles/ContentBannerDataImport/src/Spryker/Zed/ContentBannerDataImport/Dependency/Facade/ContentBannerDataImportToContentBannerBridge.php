<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentBannerDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
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
     * @param \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBannerTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentBannerTerm(
        ContentBannerTermTransfer $contentBannerTermTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentBannerFacade->validateContentBannerTerm($contentBannerTermTransfer);
    }
}
