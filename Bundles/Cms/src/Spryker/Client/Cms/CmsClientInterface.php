<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;

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
     * - Retrieves CMS version data using provided data.
     * - Calculates locale specific CMS page data.
     * - Flattens locale specific CMS page data.
     * - Expands flattened data with pre-configured CmsPageDataExpanderPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageData(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer);

}
