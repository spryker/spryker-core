<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Dependency\Client;

/**
 * @deprecated Will be removed in the next major.
 */
interface CustomersRestApiToSessionClientInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value);
}
