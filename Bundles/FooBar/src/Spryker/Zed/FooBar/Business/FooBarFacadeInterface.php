<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FooBar\Business;

interface FooBarFacadeInterface
{
    /**
     * @api
     *
     * @param string $foo
     *
     * @return bool
     */
    public function addSomething(string $foo): bool;
}
