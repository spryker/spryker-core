<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\Flysystem\FlysystemConstants;

class FlysystemConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return array
     */
    public function getFilesystemConfig()
    {
        return $this->get(FlysystemConstants::FILESYSTEM_SERVICE, []);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getFlysystemConfig()
    {
        return [];
    }
}
