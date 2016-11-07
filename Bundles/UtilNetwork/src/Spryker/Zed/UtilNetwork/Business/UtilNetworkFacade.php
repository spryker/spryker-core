<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilNetwork\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UtilNetwork\Business\UtilNetworkBusinessFactory getFactory()
 */
class UtilNetworkFacade extends AbstractFacade implements UtilNetworkFacadeInterface
{

    /**
     *
     * Specification:
     *  - Get current running script hostname
     *
     * @api
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->getFactory()
           ->createHost()
           ->getHostname();
    }

}
