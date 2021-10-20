<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Messenger;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MessengerConstants
{
    /**
     * @deprecated Use {@link \Spryker\Shared\Messenger\MessengerConfig::SESSION_TRAY} instead.
     * @var string
     */
    public const SESSION_TRAY = 'SESSION_TRAY';

    /**
     * @deprecated Use {@link \Spryker\Shared\Messenger\MessengerConfig::IN_MEMORY_TRAY} instead.
     * @var string
     */
    public const IN_MEMORY_TRAY = 'IN_MEMORY_TRAY';

    /**
     * @var string
     */
    public const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';

    /**
     * @var string
     */
    public const FLASH_MESSAGES_ERROR = 'flash.messages.error';

    /**
     * @var string
     */
    public const FLASH_MESSAGES_INFO = 'flash.messages.info';
}
