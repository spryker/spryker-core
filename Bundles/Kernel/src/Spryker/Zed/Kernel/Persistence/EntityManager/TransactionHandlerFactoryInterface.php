<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Persistence\EntityManager;

interface TransactionHandlerFactoryInterface
{
    /**
     * @return \Spryker\Zed\Kernel\Persistence\EntityManager\TransactionHandlerInterface
     */
    public function createHandler();
}
