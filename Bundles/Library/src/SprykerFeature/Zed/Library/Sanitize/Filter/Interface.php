<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface SprykerFeature_Zed_Library_Sanitize_Filter_Interface
{

    /**
     * @param array $array
     *
     * @return array
     */
    public function filter(array $array);

}
