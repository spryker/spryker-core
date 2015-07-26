<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Dependency\Plugin\ExportFailedDeciderPluginInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\ProductFrontendExporterConnectorDependencyContainer;

/**
 * @method DependencyContainerInterface|ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductExportIsFailedDeciderPlugin extends AbstractPlugin implements ExportFailedDeciderPluginInterface
{

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $result)
    {
        return $this->getDependencyContainer()->getProductExportDecider()->isExportFailed($result);
    }

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

}
