<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Dependency\Plugin;

use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;

interface ExportFailedDeciderPluginInterface
{

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $result);

    /**
     * @return string
     */
    public function getProcessableType();

}
