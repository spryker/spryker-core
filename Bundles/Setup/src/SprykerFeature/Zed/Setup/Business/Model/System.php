<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * @deprecated
 */

namespace SprykerFeature\Zed\Setup\Business\Model;

class System
{

    /**
     * @param string $what
     *
     * @return string
     */
    public function getPhpInfo($what = null)
    {
        ob_start();
        if (isset($what)) {
            phpinfo($what);
        } else {
            phpinfo();
        }

        return ob_get_clean();
    }

}
