<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Dependency\Client;

interface CartToMessengerClientInterface
{
    /**
     * Specification:
     *  - Writes error message to flash bag.
     *
     * @api
     *
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message);
}
