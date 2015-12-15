<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Heartbeat\Business\Assistant;

use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Heartbeat\Code\AbstractHealthIndicator;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Orm\Zed\Heartbeat\Persistence\SpyPropelHeartbeat;

class PropelHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{

    const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE = 'Unable to write to database';

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
