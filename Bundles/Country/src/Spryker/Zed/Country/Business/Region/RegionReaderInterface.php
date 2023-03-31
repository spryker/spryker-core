<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

interface RegionReaderInterface
{
    /**
     * @param string $isoCode
     *
     * @return bool
     */
    public function regionExists(string $isoCode): bool;
}
