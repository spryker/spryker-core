<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Decider;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;

interface ProductExportDeciderInterface
{

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $result);

}
