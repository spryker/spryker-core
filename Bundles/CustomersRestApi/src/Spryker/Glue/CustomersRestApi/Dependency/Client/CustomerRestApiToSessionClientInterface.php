<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Dependency\Client;

interface CustomerRestApiToSessionClientInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value): void;
}
