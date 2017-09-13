<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer;
use Generated\Shared\Transfer\CmsVersionDataRequestTransfer;

interface CmsClientInterface
{

    /**
     * @api
     *
     * @deprecated Use CMS Block module instead
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function findBlockByName(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * Specification:
     * - Retrieves current CMS version data with localized attributes from permanent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer);

    /**
     * Specification
     * - Applies preconfigured CmsPageDataExpanderPluginInterface plugins on provided CMS page data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer
     */
    public function expandCmsPageData(CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer);

}
