<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilGlob;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilGlob\Glob\Glob;
use Spryker\Service\UtilGlob\Glob\GlobInterface;

class UtilGlobServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilGlob\Glob\GlobInterface
     */
    public function createGlob(): GlobInterface
    {
        return new Glob();
    }
}
