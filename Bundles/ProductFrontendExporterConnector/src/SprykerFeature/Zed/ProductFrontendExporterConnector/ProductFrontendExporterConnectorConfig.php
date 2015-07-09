<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ProductFrontendExporterConnectorConfig extends AbstractBundleConfig
{

    /**
     * @return int
     */
    public function getPercentageOfFaultToleranceForExport()
    {
        return 10;
    }

}
