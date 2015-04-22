<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

/**
 * Class ProductExporterSettings
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector\Business
 */
class ProductFrontendExporterConnectorSettings
{
    /**
     * @return int
     */
    public function getPercentageOfFaultToleranceForExport()
    {
        return 10;
    }
}
