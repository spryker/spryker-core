<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

interface VersionMigrationInterface
{
    /**
     * @param string $cmsVersionOriginData
     * @param string $cmsVersionTargetData
     *
     * @return void
     */
    public function migrate($cmsVersionOriginData, $cmsVersionTargetData);
}
