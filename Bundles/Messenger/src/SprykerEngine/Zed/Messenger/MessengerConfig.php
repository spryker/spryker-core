<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessengerConfig extends AbstractBundleConfig
{

    const SESSION_TRAY = 'SESSION_TRAY';
    const IN_MEMORY_TRAY = 'IN_MEMORY_TRAY';

    /**
     * @var string
     */
    protected static $messageTray = self::SESSION_TRAY;

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
        if (PHP_SAPI === 'cli') {
            return self::IN_MEMORY_TRAY;
        }

        return self::$messageTray;
    }

}
