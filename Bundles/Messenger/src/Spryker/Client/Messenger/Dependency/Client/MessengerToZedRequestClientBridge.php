<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\Dependency\Client;

class MessengerToZedRequestClientBridge implements MessengerToZedRequestClientInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequest;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequest
     */
    public function __construct($zedRequest)
    {
        $this->zedRequest = $zedRequest;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages()
    {
        return $this->zedRequest->getLastResponseInfoMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages()
    {
        return $this->zedRequest->getLastResponseErrorMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages()
    {
        return $this->zedRequest->getLastResponseSuccessMessages();
    }
}
