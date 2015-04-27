<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * Class ProductCategoryFacade
 * @package SprykerFeature\Zed\ProductCategory\Business
 */
/**
 * @method ProductCategoryFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductCategoryFrontendExporterConnectorFacade extends AbstractFacade
{
    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     * @return array
     */
    public function processProductCategoryBreadcrumbs(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        $breadcumbProcessor = $this->getDependencyContainer()->createProductCategoryBreadcrumbProcessor();

        return $breadcumbProcessor->process($resultSet, $processedResultSet, $locale);
    }
}
