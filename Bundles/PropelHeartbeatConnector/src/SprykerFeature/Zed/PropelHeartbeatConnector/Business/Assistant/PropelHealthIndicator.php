<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PropelHeartbeatConnector\Business\Assistant;

use Generated\Shared\Transfer\HealthDetailTransfer;
use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use Generated\Shared\Transfer\HealthReportTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\SpyPropelHeartbeat;

class PropelHealthIndicator implements HealthIndicatorInterface
{

    const HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE = 'Unable to write to database';

    /**
     * @param HealthReportTransfer $healthReportTransfer
     */
    public function doHealthCheck(HealthReportTransfer $healthReportTransfer)
    {
        $healthIndicatorReport = new HealthIndicatorReportTransfer();
        $healthIndicatorReport->setName(get_class($this));
        $healthIndicatorReport->setStatus(true);

        if (!$this->canWriteToDatabase()) {
            $healthIndicatorReport->setStatus(false);
            $healthDetail = new HealthDetailTransfer();
            $healthDetail->setMessage(self::HEALTH_MESSAGE_UNABLE_TO_WRITE_TO_DATABASE);
            $healthIndicatorReport->addHealthDetail($healthDetail);
        }

        $healthReportTransfer->addHealthIndicatorReport($healthIndicatorReport);
    }

    /**
     * @return bool
     */
    private function canWriteToDatabase()
    {
        try {
            $entity = new SpyPropelHeartbeat();
            $entity->setHeartbeatCheck('ok');
            $entity->save();
            $entity->delete();
        } catch (PropelException $e) {
            return false;
        }

        return true;
    }

}
