<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilNetwork\Business;

/**
 * @method \Spryker\Zed\UtilNetwork\Business\UtilNetworkBusinessFactory getFactory()
 */
interface UtilNetworkFacadeInterface
{

    /**
     * Specification:
     *  - Get current running script hostname
     *
     * @api
     *
     * @return string
     */
    public function getHostName();

}
