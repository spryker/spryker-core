<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Mode;

use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

class ModeDetector implements ModeDetectorInterface
{

    /**
     * @return string
     */
    public function getMode()
    {
        if(\SprykerFeature_Shared_Library_Environment::isNotProduction()) {
            return self::MODE_TEST;
        }

        return self::MODE_LIVE;
    }

}
