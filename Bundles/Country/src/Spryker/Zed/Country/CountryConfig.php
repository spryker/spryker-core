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
     * Specification:
     * - Includes ISO2 codes for the territories that should be skipped during the data install process. Such territories will not be available anywhere in the system (e.g. in the data returned by CountryFacade).
     * - Refer to /data/cldr/en/territories.json for the full list of countries and codes.
     *
     * @api
     *
     * @example ['EU', 'QO']
     *
     * @return array<string>
     */
    public function getTerritoriesBlacklist(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Country\Business\Internal\Regions\RegionInstallInterface>
     */
    protected function getCountriesToInstallRegionsFor(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCldrDir(): string
    {
        return __DIR__ . '/../../../../data/cldr';
    }
}
