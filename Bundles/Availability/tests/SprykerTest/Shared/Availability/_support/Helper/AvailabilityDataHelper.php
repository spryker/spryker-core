<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\StoreBuilder;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $storeOverride
     *
     * @return \Generated\Shared\Transfer\AvailabilityTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveAvailabilityAbstract($availabilityOverride = [])
    {


        return null;
    }
}
