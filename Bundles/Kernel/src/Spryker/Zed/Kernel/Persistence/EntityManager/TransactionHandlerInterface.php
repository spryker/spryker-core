<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

use Closure;

interface TransactionHandlerInterface
{
    /**
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function handleTransaction(Closure $callback);
}
