<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Dependency\Client;

interface MultiCartToMessengerClientInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message);
}
