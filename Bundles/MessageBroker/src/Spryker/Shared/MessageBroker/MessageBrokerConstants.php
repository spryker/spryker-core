<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MessageBroker;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MessageBrokerConstants
{
    /**
     * @var string
     */
    public const MESSAGE_TO_CHANNEL_MAP = 'MESSAGE_BROKER:MESSAGE_TO_CHANNEL_MAP';

    /**
     * @deprecated Use {@link \Spryker\Shared\MessageBroker\MessageBrokerConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP} instead.
     *
     * @var string
     */
    public const CHANNEL_TO_TRANSPORT_MAP = 'MESSAGE_BROKER:SENDER_CHANNEL_TO_CLIENT_MAP';

    /**
     * @var string
     */
    public const LOGGING_ENABLED = 'MESSAGE_BROKER:LOGGING_ENABLED';

    /**
     * @var string
     */
    public const IS_ENABLED = 'MESSAGE_BROKER:IS_ENABLED';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'MESSAGE_BROKER:TENANT_IDENTIFIER';

    /**
     * @var string
     */
    public const CHANNEL_TO_RECEIVER_TRANSPORT_MAP = 'MESSAGE_BROKER:CHANNEL_TO_RECEIVER_TRANSPORT_MAP';

    /**
     * @var string
     */
    public const CHANNEL_TO_SENDER_TRANSPORT_MAP = 'MESSAGE_BROKER:CHANNEL_TO_SENDER_TRANSPORT_MAP';
}
