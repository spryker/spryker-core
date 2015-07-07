<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CustomerConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get('HOST_YVES');
    }
}
