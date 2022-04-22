<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreReference\Helper;

use Codeception\Module;
use Spryker\Shared\StoreReference\StoreReferenceConstants;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

class StoreReferenceHelper extends Module
{
    use ConfigHelperTrait;

    /**
     * @param array<string, string> $storeReferenceData
     *
     * @return void
     */
    public function setStoreReferenceData(array $storeReferenceData): void
    {
        $this->getConfigHelper()->mockEnvironmentConfig(StoreReferenceConstants::STORE_NAME_REFERENCE_MAP, $storeReferenceData);
    }
}
