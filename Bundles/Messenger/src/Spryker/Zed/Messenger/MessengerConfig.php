<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger;

use Spryker\Shared\Messenger\MessengerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MessengerConfig extends AbstractBundleConfig
{

    /**
     * @var string
     */
    protected static $messageTray = MessengerConstants::SESSION_TRAY;

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
            return MessengerConstants::IN_MEMORY_TRAY;
        }
        return self::$messageTray;
    }

}
