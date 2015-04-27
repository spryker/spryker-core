<?php


namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorFacade extends AbstractFacade
{
    /**
     * @param string $locale
     * @param array $products
     *
     * @return array
     */
    public function buildProducts(array $products, $locale)
    {
        return $this->getDependencyContainer()
            ->getProductProcessor()
            ->buildProducts($products, $locale);
    }

    /**
     * @param BatchResultInterface $result
     *
     * @return bool
     */
    public function isExportFailed(BatchResultInterface $result)
    {
        return $this->getDependencyContainer()->getProductExportDecider()->isFailed($result);
    }
}
