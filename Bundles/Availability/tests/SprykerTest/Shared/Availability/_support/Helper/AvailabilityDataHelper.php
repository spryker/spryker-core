<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $availabilityOverride
     *
     * @return null
     */
    public function haveAvailabilityAbstract($availabilityOverride = [])
    {
        return null;
    }
}
