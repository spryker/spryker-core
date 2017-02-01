<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;

interface CmsPageUrlBuilderInterface
{

    /**
     * @param CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer);

    /**
     * @param string $localeName
     *
     * @return string
     */
    public function getPageUrlPrefix($localeName);
}
