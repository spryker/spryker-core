<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * @deprecated
 */
class SprykerFeature_Zed_Setup_Business_Model_System
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
