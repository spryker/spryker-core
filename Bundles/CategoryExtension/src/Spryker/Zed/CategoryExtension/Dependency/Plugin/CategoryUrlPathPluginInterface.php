<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;

/**
 * Implement this plugin interface to update category url paths during creating/updating category url.
 */
interface CategoryUrlPathPluginInterface
{
    /**
     * Specification:
     * - Update category url paths returned array.
     *
     * @api
     *
     * @param array $paths
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function update(array $paths, LocaleTransfer $localeTransfer);
}
