<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger;

use Spryker\Shared\Messenger\MessengerConfig as SharedMessengerConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessengerConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected static $messageTray = SharedMessengerConfig::SESSION_TRAY;

    /**
     * @param string $messageTray
     *
     * @return void
     */
    public static function setMessageTray($messageTray)
    {
        self::$messageTray = $messageTray;
    }

    /**
     * @return string
     */
    public static function getTray()
    {
        if (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') {
            return SharedMessengerConfig::IN_MEMORY_TRAY;
        }

        return self::$messageTray;
    }
}
