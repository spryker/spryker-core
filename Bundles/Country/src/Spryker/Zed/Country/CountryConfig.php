<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country;

use Spryker\Zed\Kernel\AbstractBundleConfig;

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
     * @return \Spryker\Zed\Country\Business\Internal\Regions\RegionInstallInterface[]
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
