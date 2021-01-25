<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CountryConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
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

    /**
     * @api
     *
     * @return string
     */
    public function getCldrDir()
    {
        return __DIR__ . '/Business/File/cldr';
    }
}
