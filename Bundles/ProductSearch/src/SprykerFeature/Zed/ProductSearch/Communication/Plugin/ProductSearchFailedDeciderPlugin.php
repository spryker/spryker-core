<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\ExportFailedDeciderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

class ProductSearchFailedDeciderPlugin extends AbstractPlugin implements ExportFailedDeciderPluginInterface
{

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $result)
    {
        return false;
    }

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

}
