<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList\Dependency\Client;

interface ShoppingListToSessionClientInterface
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name);
}
