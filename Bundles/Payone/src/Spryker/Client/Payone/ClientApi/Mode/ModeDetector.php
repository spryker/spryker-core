<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payone\ClientApi\Mode;

use Spryker\Shared\Config\Environment;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @deprecated Use Zed one instead
 */
class ModeDetector implements ModeDetectorInterface
{

    /**
     * @deprecated Will be removed. Needs refactoring to use Zed getMode().
     *
     * @return string
     */
    public function getMode()
    {
        if (Environment::isNotProduction()) {
            return self::MODE_TEST;
        }

        return self::MODE_LIVE;
    }

}
