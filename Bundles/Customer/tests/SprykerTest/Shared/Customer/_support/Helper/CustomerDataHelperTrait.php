<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Customer\Helper;

use Codeception\Module;

trait CustomerDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Customer\Helper\CustomerDataHelper
     */
    protected function getCustomerDataHelper(): CustomerDataHelper
    {
        /** @var \SprykerTest\Shared\Customer\Helper\CustomerDataHelper $customerDataHelper */
        $customerDataHelper = $this->getModule('\\' . CustomerDataHelper::class);

        return $customerDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
