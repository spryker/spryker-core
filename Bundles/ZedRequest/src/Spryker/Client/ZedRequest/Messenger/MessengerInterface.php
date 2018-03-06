<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Messenger;

interface MessengerInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages();

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest(): void;
}
