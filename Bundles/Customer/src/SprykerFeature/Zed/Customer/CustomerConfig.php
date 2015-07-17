<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;

class CustomerConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(SystemConfig::HOST_YVES);
    }

}
