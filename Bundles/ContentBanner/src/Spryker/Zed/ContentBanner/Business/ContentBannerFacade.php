<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ContentBanner\Business\ContentBannerBusinessFactory getFactory()
 */
class ContentBannerFacade extends AbstractFacade implements ContentBannerFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentBannerTerm(ContentBannerTransfer $contentBannerTransfer): ContentValidationResponseTransfer
    {
        return $this->getFactory()->createContentBannerValidator()->validateContentBannerTerm($contentBannerTransfer);
    }
}
