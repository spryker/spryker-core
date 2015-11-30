<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class FlashMessengerConfig extends AbstractBundleConfig
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
        return self::$messageTray;
    }

}
