<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country;

use SprykerFeature\Zed\Country\Business\Internal\Regions\RegionInstallInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class CountryConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getTerritoriesBlacklist()
    {
        return [
            'EU', // Europe
            'QO', // Outlying Oceania
            'ZZ', // undefined
        ];
    }

    /**
     * @return RegionInstallInterface[]
     */
    protected function getCountriesToInstallRegionsFor()
    {
        return [];
    }

    public function getCldrDir()
    {
        return __DIR__ . '/Business/File/cldr';
    }

}
