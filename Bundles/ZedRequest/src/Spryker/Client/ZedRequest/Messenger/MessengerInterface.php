<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Messenger;

interface MessengerInterface
{
    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages();

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages();

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages();

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getResponsesInfoMessages(): array;

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getResponsesErrorMessages(): array;

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getResponsesSuccessMessages(): array;

    /**
     * @return void
     */
    public function addAllResponseMessagesToMessenger(): void;
}
