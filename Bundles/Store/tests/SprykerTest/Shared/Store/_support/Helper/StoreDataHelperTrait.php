<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;

trait StoreDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Store\Helper\StoreDataHelper
     */
    protected function getStoreDataHelper(): StoreDataHelper
    {
        /** @var \SprykerTest\Shared\Store\Helper\StoreDataHelper $storeDataHelper */
        $storeDataHelper = $this->getModule('\\' . StoreDataHelper::class);

        return $storeDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
