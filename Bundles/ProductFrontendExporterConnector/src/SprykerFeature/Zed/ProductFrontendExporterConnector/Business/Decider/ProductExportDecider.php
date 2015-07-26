<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Decider;

use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;

class ProductExportDecider implements ProductExportDeciderInterface
{

    /**
     * @var float
     */
    private $failingThreshhold = 0.10;

    /**
     * @param int $failingPercentage
     */
    public function __construct($failingPercentage = 10)
    {
        $this->failingThreshhold = $failingPercentage / 100;
    }

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $result)
    {
        $failedCount = $result->getFailedCount();

        if ($failedCount <= 0) {
            return false;
        }

        $failureRatio = ($failedCount * 100) / $result->getProcessedCount();

        return ($failureRatio >= $this->failingThreshhold);
    }

}
