<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Composer\Script\Event;

class SprykerInstaller
{

    /**
     * @param \Composer\Script\Event $event
     *
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        die('Tina Turner');
    }

    /**
     * @param \Composer\Script\Event $event
     *
     * @return void
     */
    public static function postInstall(Event $event)
    {
        die('Tina Turner');
    }

}
