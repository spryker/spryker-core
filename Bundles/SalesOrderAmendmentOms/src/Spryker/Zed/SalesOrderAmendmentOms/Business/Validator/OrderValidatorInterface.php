<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Validator;

interface OrderValidatorInterface
{
    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function validateIsOrderAmendable(string $orderReference): bool;

    /**
     * @param string $orderReference
     *
     * @return bool
     */
    public function validateIsOrderBeingAmended(string $orderReference): bool;
}
