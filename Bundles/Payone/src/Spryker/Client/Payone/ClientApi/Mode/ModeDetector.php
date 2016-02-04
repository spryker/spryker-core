<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payone\ClientApi\Mode;

use Spryker\Shared\Library\Environment;
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
