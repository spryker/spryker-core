<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

interface CountryRepositoryInterface
{
    /**
     * @param string $iso2Code
     *
     * @return string[]
     */
    public function getRegionsByCountryIso2Code(string $iso2Code): array;
}
