<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Mode;

use SprykerFeature\Shared\Library\Environment;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @deprecated Use Zed one instead
 */
class ModeDetector implements ModeDetectorInterface
{

    /**
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
