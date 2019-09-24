<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Internal\Regions;

interface RegionInstallInterface
{
    /**
     * key: iso3661-2 code
     * value: name
     *
     * Regex for wikipedia to php:
     * .*?([^\s]+)\s+(..-.{1,3})
     *             '$2' => '$1',
     *
     * @return string[]
     */
    public function getCodeArray();

    /**
     * iso3661 alpha 2 code for country
     *
     * @return string
     */
    public function getCountryIso();
}
