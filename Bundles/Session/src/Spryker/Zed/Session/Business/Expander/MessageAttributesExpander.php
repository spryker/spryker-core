<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Expander;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Client\Session\SessionClientInterface;

class MessageAttributesExpander implements MessageAttributesExpanderInterface
{
    /**
     * @var string
     */
    protected const SESSION_TRACKING_ID = 'sessionTrackingId';

    /**
     * @var string
     */
    protected static $cachedSessionTrackingId;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expand(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        if ($messageAttributesTransfer->getSessionTrackingId()) {
            return $messageAttributesTransfer;
        }

        $this->persistSessionTrackingId();

        return $messageAttributesTransfer->setSessionTrackingId(static::$cachedSessionTrackingId);
    }

    /**
     * @return void
     */
    protected function persistSessionTrackingId(): void
    {
        if (static::$cachedSessionTrackingId) {
            return;
        }

        static::$cachedSessionTrackingId = Uuid::uuid4()->toString();
        if (!$this->sessionClient->isStarted()) {
            return;
        }

        $sessionTrackingId = $this->sessionClient->get(static::SESSION_TRACKING_ID);
        if ($sessionTrackingId) {
            static::$cachedSessionTrackingId = $sessionTrackingId;

            return;
        }

        $this->sessionClient->set(static::SESSION_TRACKING_ID, static::$cachedSessionTrackingId);
    }
}
