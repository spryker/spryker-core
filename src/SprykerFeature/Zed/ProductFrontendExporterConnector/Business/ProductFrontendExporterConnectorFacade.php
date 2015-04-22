<?php


namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * Class ProductFrontendExporterConnectorFacade
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector
 */
/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorFacade extends AbstractFacade
{
    /**
     * @param array  $products
     * @param string $locale
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
