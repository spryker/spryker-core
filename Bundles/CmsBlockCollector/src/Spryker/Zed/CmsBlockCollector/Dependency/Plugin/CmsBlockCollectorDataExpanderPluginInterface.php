<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsBlockCollectorDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Allows providing additional data before exporting to Yves data store.
     *
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expand(array $collectedData, LocaleTransfer $localeTransfer);
}
