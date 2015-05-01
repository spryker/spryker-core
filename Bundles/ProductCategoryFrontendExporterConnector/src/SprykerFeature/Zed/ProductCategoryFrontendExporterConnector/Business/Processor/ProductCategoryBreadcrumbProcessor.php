<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Dependency\Facade\ProductCategoryFrontendExporterToCategoryExporterInterface;

class ProductCategoryBreadcrumbProcessor implements ProductCategoryBreadcrumbProcessorInterface
{
    /**
     * @var ProductCategoryFrontendExporterToCategoryExporterInterface
     */
    protected $nodeExploder;

    /**
     * @param ProductCategoryFrontendExporterToCategoryExporterInterface $nodeExploder
     */
    public function __construct(ProductCategoryFrontendExporterToCategoryExporterInterface $nodeExploder)
    {
        $this->nodeExploder = $nodeExploder;
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function process(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        foreach ($resultSet as $index => $product) {
            $processedResultSet[$index]['category'] = $this->nodeExploder->explodeGroupedNodes(
                $product,
                'category_parent_ids',
                'category_parent_names',
                'category_parent_urls'
            );
        }

        return $processedResultSet;
    }
}
