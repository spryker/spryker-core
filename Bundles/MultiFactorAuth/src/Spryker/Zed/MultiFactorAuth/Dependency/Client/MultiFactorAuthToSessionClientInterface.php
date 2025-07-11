<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Dependency\Client;

interface MultiFactorAuthToSessionClientInterface
{
    /**
     * @param string $name
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null);
}
