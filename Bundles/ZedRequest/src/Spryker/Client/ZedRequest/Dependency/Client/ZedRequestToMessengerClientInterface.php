<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Dependency\Client;

interface ZedRequestToMessengerClientInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage(string $message): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage(string $message): void;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage(string $message): void;
}
