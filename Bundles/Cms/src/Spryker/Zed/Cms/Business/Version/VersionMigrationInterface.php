<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

interface VersionMigrationInterface
{

    /**
     * @param string $cmsVersionOriginData
     * @param string $cmsVersionTargetData
     *
     * @return bool
     */
    public function migrate($cmsVersionOriginData, $cmsVersionTargetData);
}
