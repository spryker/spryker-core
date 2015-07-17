<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Dependency\Plugin;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;

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
