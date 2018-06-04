<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\BusinessOnBehalf\Helper;

use Codeception\Module;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class BusinessOnBehalfDataHelper extends Module
{
    use LocatorHelperTrait;
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $expected
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $actual
     * @param string $message
     *
     * @return void
     */
    public function assertTransferEquals(AbstractTransfer $expected, AbstractTransfer $actual, string $message = '')
    {
        $expectedArray = $expected->toArray();
        $actualArray = $actual->toArray();

        $this->assertEquals($expectedArray, $actualArray, $message);
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface
     */
    public function getFacade()
    {
        return $this->getLocator()->businessOnBehalf()->facade();
    }
}
