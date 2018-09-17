<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession\Dependency\Client;

interface ShoppingListSessionToSessionClientInterface
{
    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set($name, $value);

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($name, $default = null);
}
