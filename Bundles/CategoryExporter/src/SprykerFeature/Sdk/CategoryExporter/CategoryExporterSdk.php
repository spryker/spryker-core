<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\CategoryExporter;

use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryExporterSdk extends AbstractSdk
{
    /**
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale)
    {
        return $this->getDependencyContainer()->createNavigation()->getCategories($locale);
    }

    /**
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale)
    {
        return $this->getDependencyContainer()->createCategoryTreeBuilder()->createTreeFromCategoryNode($categoryNode, $locale);
    }
}
