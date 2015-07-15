<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductCategoryFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductCategoryFrontendExporterConnectorFacade extends AbstractFacade
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processProductCategoryBreadcrumbs(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        $breadcumbProcessor = $this->getDependencyContainer()->createProductCategoryBreadcrumbProcessor();

        return $breadcumbProcessor->process($resultSet, $processedResultSet, $locale);
    }

}
