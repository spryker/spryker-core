<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Dependency\Plugin;

interface CmsBlockStorageDataExpanderPluginInterface
{

    /**
     *
     * Specification:
     *  - Allows providing additional data before exporting to Yves data store
     *
     * @api
     *
     * @param array $cmsBlockData
     *
     * @return array
     */
    public function expand(array $cmsBlockData);
}
