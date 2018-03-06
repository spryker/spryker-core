<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Customer\Helper;

trait CustomerDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Customer\Helper\CustomerDataHelper
     */
    private function getCustomerDataHelper()
    {
        return $this->getModule('\\' . CustomerDataHelper::class);
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
