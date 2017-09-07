<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsCollector;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;

interface CmsCollectorClientInterface
{

    /**
     * Specification
     * - Expands collector data with configured collector data expander plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorData(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer);

}
