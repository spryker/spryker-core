<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PropelHeartbeatConnector\Business\Assistant;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\Heartbeat\Code\AbstractHealthIndicator;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use Orm\Zed\PropelHeartbeatConnector\Persistence\SpyPropelHeartbeat;

class PropelHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{

    const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE = 'Unable to write to database';

    public function healthCheck()
    {
        $this->checkWriteToDatabase();
    }

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
