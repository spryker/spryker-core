<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Key;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface HashGeneratorInterface
{

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateParamHash(AbstractRequestContainer $request, $securityKey);

}
