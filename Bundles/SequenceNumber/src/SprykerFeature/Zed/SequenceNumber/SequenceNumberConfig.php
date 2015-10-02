<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SequenceNumberConfig extends AbstractBundleConfig
{

    /**
     * @return int
     */
    public function getNumberLength() {
        return 0;
    }

    /**
     * @return int
     */
    public function getMinimumNumber()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getNumberIncrementMin()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getNumberIncrementMax()
    {
        return 1;
    }

}
