<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryBreadcrumbProcessorInterface
{

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleTransfer $locale);

}
