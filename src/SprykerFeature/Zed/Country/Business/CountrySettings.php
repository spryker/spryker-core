<?php

namespace SprykerFeature\Zed\Country\Business;

use SprykerFeature\Zed\Country\Business\Internal\Regions\RegionInstallInterface;

class CountrySettings
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
        return __DIR__ . '/File/cldr';
    }
}
