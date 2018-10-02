<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Orm\Zed\Heartbeat\Persistence\SpyPropelHeartbeat;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class PropelHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE = 'Unable to write to database';

    /**
     * @return void
     */
    public function healthCheck()
    {
        $this->checkWriteToDatabase();
    }

    /**
     * @return void
     */
    private function checkWriteToDatabase()
    {
        try {
            $entity = new SpyPropelHeartbeat();
            $entity->setHeartbeatCheck('ok');
            $entity->save();
            $entity->delete();
        } catch (PropelException $e) {
            $this->addDysfunction(self::HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE);
            $this->addDysfunction($e->getMessage());
        }
    }
}
