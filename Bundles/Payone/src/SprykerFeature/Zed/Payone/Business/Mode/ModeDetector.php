<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Mode;

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
        // @todo we need order transfer interface from sales...?!?!
        // @todo do we still get is_test flag from order? can we rely on?
        if ($this->orderInterface->getIsTest()) {
            return  self::MODE_TEST;
        }

        return self::MODE_LIVE;
    }

}
