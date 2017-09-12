<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Plugin;

use Symfony\Component\HttpFoundation\Request;

interface ControllerResponseExpanderPluginInterface
{

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getResult(Request $request);

}
