<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Exception;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class SessionHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const HEALTH_MESSAGE_UNABLE_TO_WRITE_SESSION = 'Unable to write session';
    public const HEALTH_MESSAGE_UNABLE_TO_READ_SESSION = 'Unable to read session';
    public const KEY_HEARTBEAT = 'heartbeat';

    /**
     * @return void
     */
    public function healthCheck()
    {
        $this->checkWriteSession();
        $this->checkReadSession();
    }

    /**
     * @return void
     */
    private function checkWriteSession()
    {
        try {
            $_SESSION[self::KEY_HEARTBEAT] = 'ok';
        } catch (Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_WRITE_SESSION);
            $this->addDysfunction($e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function checkReadSession()
    {
        try {
            $status = $_SESSION[self::KEY_HEARTBEAT];
        } catch (Exception $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_READ_SESSION);
            $this->addDysfunction($e->getMessage());
        }
    }
}
