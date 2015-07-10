<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;

class SprykerFeature_Zed_Library_Password_Config
{

    /**
     * @return int
     * @static
     */
    public static function getAlgorithm()
    {

        $algorithm = Config::get(SystemConfig::ZED_LIBRARY_PASSWORD_ALGORITHM);
        if(empty($algorithm)){
            $algorithm = PASSWORD_DEFAULT;
        }

        return $algorithm;
    }

    /**
     * @return array
     * @static
     */
    public static function getAlgorithmOptions()
    {

        $options = Config::get(SystemConfig::ZED_LIBRARY_PASSWORD_OPTIONS);
        if(empty($options)){
            $options = [];

        }

        return $options;
    }

}
