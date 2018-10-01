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
     * @deprecated use \Spryker\Shared\Messenger\MessengerConfig::SESSION_TRAY instead
     */
    public const SESSION_TRAY = 'SESSION_TRAY';
    /**
     * @deprecated use \Spryker\Shared\Messenger\MessengerConfig::IN_MEMORY_TRAY instead
     */
    public const IN_MEMORY_TRAY = 'IN_MEMORY_TRAY';

    public const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';
    public const FLASH_MESSAGES_ERROR = 'flash.messages.error';
    public const FLASH_MESSAGES_INFO = 'flash.messages.info';
}
