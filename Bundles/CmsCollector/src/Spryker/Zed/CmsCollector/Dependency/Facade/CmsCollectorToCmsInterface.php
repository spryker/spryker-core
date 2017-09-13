<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsCollectorToCmsInterface
{

    /**
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expandCmsPageData(array $cmsPageData, LocaleTransfer $localeTransfer);

}
