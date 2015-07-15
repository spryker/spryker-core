<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorFacade extends AbstractFacade
{

    /**
     * @param array $products
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $products, LocaleTransfer $locale)
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
