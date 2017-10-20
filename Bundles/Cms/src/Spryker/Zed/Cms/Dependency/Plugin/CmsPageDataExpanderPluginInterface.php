<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsPageDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided CMS page data.
     *
     * @api
     *
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expand(array $cmsPageData, LocaleTransfer $localeTransfer);
}
