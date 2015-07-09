<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business\Internal\Regions;

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
     * @return array
     */
    public function getCodeArray();

    /**
     * iso3661 alpha 2 code for country
     *
     * @return string
     */
    public function getCountryIso();

}
