<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper;

/**
 * Use this interface on a helper that is used for messages that will be sent by this application.
 */
interface SendHelperInterface
{
    /**
     * @return void
     */
    public function setUpMessageBroker(): void;

    /**
     * @return void
     */
    public function preSendMessage(): void;

    /**
     * @return void
     */
    public function sendMessage(): void;

    /**
     * @return void
     */
    public function postSendMessage(): void;
}
