<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Assertion\Business;

use Spryker\Zed\Assertion\Business\Model\Assertion;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Assertion\AssertionConfig getConfig()
 */
class AssertionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Assertion\Business\Model\Assertion
     */
    public function createAssertion()
    {
        return new Assertion();
    }
}
