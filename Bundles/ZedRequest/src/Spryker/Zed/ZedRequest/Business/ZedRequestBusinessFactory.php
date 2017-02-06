<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;

class ZedRequestBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ZedRequest\Business\Model\Repeater
     */
    public function createRepeater()
    {
        return new Repeater();
    }

}
