<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\ZedRequest;

use Spryker\Client\Messenger\Dependency\Client\MessengerToZedRequestClientInterface;
use Spryker\Client\Messenger\FlashBag\FlashBagInterface;

class Messages implements MessagesInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestStub;

    /**
     * @var \Spryker\Client\Messenger\FlashBag\FlashBagInterface
     */
    protected $flashBag;

    /**
     * @param \Spryker\Client\Messenger\Dependency\Client\MessengerToZedRequestClientInterface $zedRequestStub
     * @param \Spryker\Client\Messenger\FlashBag\FlashBagInterface $flashBag
     */
    public function __construct(
        MessengerToZedRequestClientInterface $zedRequestStub,
        FlashBagInterface $flashBag
    ) {
        $this->zedRequestStub = $zedRequestStub;
        $this->flashBag = $flashBag;
    }

    /**
     * @return void
     */
    public function processFlashMessagesFromLastZedRequest(): void
    {
        $this->processErrorMessages();
        $this->processSuccessMessages();
        $this->processInfoMessages();
    }

    /**
     * @return void
     */
    protected function processErrorMessages(): void
    {
        foreach ($this->zedRequestStub->getLastResponseErrorMessages() as $errorMessage) {
            $this->flashBag->addErrorMessage($errorMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function processSuccessMessages(): void
    {
        foreach ($this->zedRequestStub->getLastResponseSuccessMessages() as $successMessage) {
            $this->flashBag->addSuccessMessage($successMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function processInfoMessages(): void
    {
        foreach ($this->zedRequestStub->getLastResponseInfoMessages() as $infoMessage) {
            $this->flashBag->addInfoMessage($infoMessage->getValue());
        }
    }
}
