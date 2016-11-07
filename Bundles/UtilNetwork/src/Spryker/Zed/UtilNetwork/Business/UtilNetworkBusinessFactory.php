<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilNetwork\Business;

use Spryker\Shared\UtilNetwork\Host;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\UtilNetwork\UtilNetworkConfig getConfig()
 */
class UtilNetworkBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\UtilNetwork\HostInterface
     */
    public function createHost()
    {
        return new Host();
    }

}
